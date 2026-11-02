<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\Supplier;
use App\Models\Tax;
use App\Services\ProductService;
use App\Support\CurrencyHelper;
use App\Support\Units;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function __construct(protected ProductService $service) {}

    public function index()
    {
        $this->authorize('view-products');

        return view('products.index', ['columns' => [
            0 => [
                'label' => 'SKU',
                'data' => 'sku',
            ],
            1 => [
                'label' => 'Name',
                'data' => 'name',
            ],
            2 => [
                'label' => 'Buy Price',
                'data' => 'buy_price',
            ],
            3 => [
                'label' => 'Sell Price',
                'data' => 'sell_price',
            ],
            4 => [
                'label' => 'Unit',
                'data' => 'unit',
            ],
            5 => [
                'label' => 'Pack Qty',
                'data' => 'pack_qty',
            ],
            6 => [
                'label' => 'Pack Type',
                'data' => 'pack_type',
            ],
            7 => [
                'label' => 'Weight (kg)',
                'data' => 'weight_kg',
            ],
            8 => [
                'label' => 'Dimensions',
                'data' => 'dimensions',
            ],
        ]]);
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-products');

        return DataTables::eloquent($this->service->query())
            ->addIndexColumn()
            ->addColumn('actions', function (Product $row) {
                return view('products.partials.actions', ['row' => $row])->render();
            })
            ->editColumn('buy_price', fn (Product $row) => CurrencyHelper::formatBase($row->buy_price))
            ->editColumn('sell_price', fn (Product $row) => CurrencyHelper::formatBase($row->sell_price))
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function show(Product $product)
    {
        $this->authorize('view-products');
        $product->load(['brand', 'category', 'supplier', 'currency', 'tax', 'images', 'documents']);

        return view('products.show', compact('product'));
    }

    public function create()
    {
        $this->authorize('create-products');
        $relations['brand_id'] = Brand::pluck('name', 'id');
        $relations['category_id'] = ProductCategory::pluck('name', 'id');
        $relations['supplier_id'] = Supplier::pluck('company_name', 'id');
        $relations['currency_id'] = Currency::pluck('code', 'id');
        $relations['tax_id'] = Tax::pluck('name', 'id');
        $relations['units'] = Units::all();

        return view('products.create', ['relations' => $relations]);
    }

    public function store(StoreProductRequest $request)
    {
        $this->authorize('create-products');

        $this->service->create($request->validated());

        return redirect()->route('products.index')->with('success', 'Created successfully.');
    }

    public function edit(Product $product)
    {
        $this->authorize('update-products');
        $relations['brand_id'] = Brand::pluck('name', 'id');
        $relations['category_id'] = ProductCategory::pluck('name', 'id');
        $relations['supplier_id'] = Supplier::pluck('company_name', 'id');
        $relations['currency_id'] = Currency::pluck('code', 'id');
        $relations['tax_id'] = Tax::pluck('name', 'id');
        $relations['units'] = Units::all();

        return view('products.edit', ['product' => $product, 'relations' => $relations]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update-products');

        $this->service->update($product->id, $request->validated());

        return redirect()->route('products.index')->with('success', 'Updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete-products');

        $this->service->delete($product->id);

        return redirect()->route('products.index')->with('success', 'Deleted successfully.');
    }

    public function storeImages(Request $request, Product $product)
    {
        $this->authorize('update-products');

        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'image|max:4096',
        ]);

        $sortOrder = (int) $product->images()->max('sort_order');
        $hasPrimary = $product->images()->where('is_primary', true)->exists();

        foreach ($request->file('images') as $file) {
            $path = $file->store('products/'.$product->id, 'public');

            $product->images()->create([
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'is_primary' => ! $hasPrimary,
                'sort_order' => ++$sortOrder,
            ]);

            $hasPrimary = true;
        }

        return back()->with('success', 'Product image(s) uploaded.');
    }

    public function primaryImage(Product $product, ProductImage $image)
    {
        $this->authorize('update-products');

        abort_unless($image->product_id === $product->id, 404);

        DB::transaction(function () use ($product, $image) {
            $product->images()->update(['is_primary' => false]);
            $image->update(['is_primary' => true]);
        });

        return back()->with('success', 'Primary image updated.');
    }

    public function destroyImage(Product $product, ProductImage $image)
    {
        $this->authorize('update-products');

        abort_unless($image->product_id === $product->id, 404);

        Storage::disk('public')->delete($image->path);

        $wasPrimary = $image->is_primary;
        $image->delete();

        if ($wasPrimary) {
            $product->images()->orderBy('sort_order')->first()?->update(['is_primary' => true]);
        }

        return back()->with('success', 'Product image deleted.');
    }
}
