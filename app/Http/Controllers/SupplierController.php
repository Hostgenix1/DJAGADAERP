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
    'label' => 'Currency',
    'data' => 'currency_id',
  ),
)]);
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-suppliers');

        return DataTables::eloquent($this->service->query()->with('currency'))
            ->addIndexColumn()
            ->addColumn('actions', function (Supplier $row) {
                return view('suppliers.partials.actions', ['row' => $row])->render();
            })
            ->editColumn('currency_id', fn (Supplier $s) => $s->currency?->code ?? '-')
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function show(Supplier $supplier)
    {
        $this->authorize('view-suppliers');

        $supplier->load(['currency', 'purchaseOrders', 'bills', 'payments', 'documents', 'supplierPrices.product', 'supplierPrices.currency']);

        $totalPo = $supplier->purchaseOrders->where('status', '!=', 'cancelled')->sum('total');
        $totalBilled = $supplier->bills->where('status', '!=', 'cancelled')->sum('total');
        $totalPaid = $supplier->bills->where('status', '!=', 'cancelled')->sum('paid_amount');
        $outstanding = $totalBilled - $totalPaid;
        $paymentsTotal = $supplier->payments->sum('amount');

        return view('suppliers.show', compact('supplier', 'totalPo', 'totalBilled', 'totalPaid', 'outstanding', 'paymentsTotal'));
    }

    public function create()
    {
        $this->authorize('create-suppliers');

        $currencies = \App\Models\Currency::where('is_active', true)->pluck('code', 'id');
        $paymentTerms = \App\Support\PaymentTerms::all();

        return view('suppliers.create', compact('currencies', 'paymentTerms'));
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

        $currencies = \App\Models\Currency::where('is_active', true)->pluck('code', 'id');
        $paymentTerms = \App\Support\PaymentTerms::all();

        return view('suppliers.edit', compact('supplier', 'currencies', 'paymentTerms'));
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
