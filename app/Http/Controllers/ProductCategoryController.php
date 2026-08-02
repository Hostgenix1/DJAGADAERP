<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use App\Models\ProductCategory;
use App\Services\ProductCategoryService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductCategoryController extends Controller
{
    public function __construct(protected ProductCategoryService $service)
    {
    }

    public function index()
    {
        $this->authorize('view-categories');

        return view('product_categories.index', ['columns' => array (
  0 => 
  array (
    'label' => 'Name',
    'data' => 'name',
  ),
)]);
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-categories');

        return DataTables::eloquent($this->service->query())
            ->addIndexColumn()
            ->addColumn('actions', function (ProductCategory $row) {
                return view('product_categories.partials.actions', ['row' => $row])->render();
            })
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-categories');

        return view('product_categories.create', );
    }

    public function store(StoreProductCategoryRequest $request)
    {
        $this->authorize('create-categories');

        $this->service->create($request->validated());

        return redirect()->route('product_categories.index')->with('success', 'Created successfully.');
    }

    public function edit(ProductCategory $product_category)
    {
        $this->authorize('update-categories');

        return view('product_categories.edit', ['product_category' => $product_category, 'relations' => $relations]);
    }

    public function update(UpdateProductCategoryRequest $request, ProductCategory $product_category)
    {
        $this->authorize('update-categories');

        $this->service->update($product_category->id, $request->validated());

        return redirect()->route('product_categories.index')->with('success', 'Updated successfully.');
    }

    public function destroy(ProductCategory $product_category)
    {
        $this->authorize('delete-categories');

        $this->service->delete($product_category->id);

        return redirect()->route('product_categories.index')->with('success', 'Deleted successfully.');
    }
}
