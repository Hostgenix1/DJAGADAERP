<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
{
    public function __construct(protected BrandService $service)
    {
    }

    public function index()
    {
        $this->authorize('view-brands');

        return view('brands.index', ['columns' => array (
  0 => 
  array (
    'label' => 'Name',
    'data' => 'name',
  ),
  1 => 
  array (
    'label' => 'Slug',
    'data' => 'slug',
  ),
)]);
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-brands');

        return DataTables::eloquent($this->service->query())
            ->addIndexColumn()
            ->addColumn('actions', function (Brand $row) {
                return view('brands.partials.actions', ['row' => $row])->render();
            })
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-brands');

        return view('brands.create', );
    }

    public function store(StoreBrandRequest $request)
    {
        $this->authorize('create-brands');

        $this->service->create($request->validated());

        return redirect()->route('brands.index')->with('success', 'Created successfully.');
    }

    public function edit(Brand $brand)
    {
        $this->authorize('update-brands');

        $relations = [];

        return view('brands.edit', ['brand' => $brand, 'relations' => $relations]);
    }

    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $this->authorize('update-brands');

        $this->service->update($brand->id, $request->validated());

        return redirect()->route('brands.index')->with('success', 'Updated successfully.');
    }

    public function destroy(Brand $brand)
    {
        $this->authorize('delete-brands');

        $this->service->delete($brand->id);

        return redirect()->route('brands.index')->with('success', 'Deleted successfully.');
    }
}
