<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function __construct(protected ProductService $service)
    {
    }

    public function index()
    {
        $this->authorize('view-products');

        return view('products.index', ['columns' => array (
  0 => 
  array (
    'label' => 'SKU',
    'data' => 'sku',
  ),
  1 => 
  array (
    'label' => 'Name',
    'data' => 'name',
  ),
  2 => 
  array (
    'label' => 'Buy Price',
    'data' => 'buy_price',
  ),
  3 => 
  array (
    'label' => 'Sell Price',
    'data' => 'sell_price',
  ),
  4 => 
  array (
    'label' => 'Unit',
    'data' => 'unit',
  ),
  5 => 
  array (
    'label' => 'Pack Qty',
    'data' => 'pack_qty',
  ),
  6 => 
  array (
    'label' => 'Pack Type',
    'data' => 'pack_type',
  ),
  7 => 
  array (
    'label' => 'Weight (kg)',
    'data' => 'weight_kg',
  ),
  8 => 
  array (
    'label' => 'Dimensions',
    'data' => 'dimensions',
  ),
  9 => 
  array (
    'label' => 'Active',
    'data' => 'is_active',
  ),
)]);
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-products');

        return DataTables::eloquent($this->service->query())
            ->addIndexColumn()
            ->addColumn('actions', function (Product $row) {
                return view('products.partials.actions', ['row' => $row])->render();
            })
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
    $relations['brand_id'] = \App\Models\Brand::pluck('name', 'id');
    $relations['category_id'] = \App\Models\ProductCategory::pluck('name', 'id');
    $relations['supplier_id'] = \App\Models\Supplier::pluck('company_name', 'id');
    $relations['currency_id'] = \App\Models\Currency::pluck('code', 'id');
    $relations['tax_id'] = \App\Models\Tax::pluck('name', 'id');
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
    $relations['brand_id'] = \App\Models\Brand::pluck('name', 'id');
    $relations['category_id'] = \App\Models\ProductCategory::pluck('name', 'id');
    $relations['supplier_id'] = \App\Models\Supplier::pluck('company_name', 'id');
    $relations['currency_id'] = \App\Models\Currency::pluck('code', 'id');
    $relations['tax_id'] = \App\Models\Tax::pluck('name', 'id');
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
}
