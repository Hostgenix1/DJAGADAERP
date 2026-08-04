<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use App\Services\SupplierService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    public function __construct(protected SupplierService $service)
    {
    }

    public function index()
    {
        $this->authorize('view-suppliers');

        return view('suppliers.index', ['columns' => array (
  0 => 
  array (
    'label' => 'Company Name',
    'data' => 'company_name',
  ),
  1 => 
  array (
    'label' => 'Contact Person',
    'data' => 'contact_person',
  ),
  2 => 
  array (
    'label' => 'Email',
    'data' => 'email',
  ),
  3 => 
  array (
    'label' => 'Phone',
    'data' => 'phone',
  ),
  4 => 
  array (
    'label' => 'City',
    'data' => 'city',
  ),
  5 => 
  array (
    'label' => 'Country',
    'data' => 'country',
  ),
  6 => 
  array (
    'label' => 'Payment Terms',
    'data' => 'payment_terms',
  ),
  7 => 
  array (
    'label' => 'Active',
    'data' => 'is_active',
  ),
)]);
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-suppliers');

        return DataTables::eloquent($this->service->query())
            ->addIndexColumn()
            ->addColumn('actions', function (Supplier $row) {
                return view('suppliers.partials.actions', ['row' => $row])->render();
            })
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-suppliers');

        return view('suppliers.create', );
    }

    public function store(StoreSupplierRequest $request)
    {
        $this->authorize('create-suppliers');

        $this->service->create($request->validated());

        return redirect()->route('suppliers.index')->with('success', 'Created successfully.');
    }

    public function edit(Supplier $supplier)
    {
        $this->authorize('update-suppliers');

        $relations = [];

        return view('suppliers.edit', ['supplier' => $supplier, 'relations' => $relations]);
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $this->authorize('update-suppliers');

        $this->service->update($supplier->id, $request->validated());

        return redirect()->route('suppliers.index')->with('success', 'Updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $this->authorize('delete-suppliers');

        $this->service->delete($supplier->id);

        return redirect()->route('suppliers.index')->with('success', 'Deleted successfully.');
    }
}
