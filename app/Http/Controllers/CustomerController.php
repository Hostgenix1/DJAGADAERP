<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Services\CustomerService;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function __construct(protected CustomerService $service)
    {
    }

    public function index()
    {
        $this->authorize('view-customers');

        return view('customers.index', ['columns' => array (
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
    'label' => 'VAT / TRN',
    'data' => 'tax_registration_number',
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
        $this->authorize('view-customers');

        return DataTables::eloquent($this->service->query())
            ->addIndexColumn()
            ->addColumn('actions', function (Customer $row) {
                return view('customers.partials.actions', ['row' => $row])->render();
            })
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function show(Customer $customer)
    {
        $this->authorize('view-customers');

        $customer->load(['contacts', 'documents' => fn ($q) => $q->where('is_latest', true)->latest('id')]);

        $invoices = $customer->invoices()->with('currency')->latest()->get();
        $payments = $customer->payments()->with('currency')->latest()->get();
        $totalInvoiced = $invoices->where('status', '!=', 'cancelled')->sum('total');
        $totalPaid = $invoices->where('status', '!=', 'cancelled')->sum('paid_amount');
        $outstanding = $totalInvoiced - $totalPaid;

        $timeline = collect();
        $timeline->push($customer->communications->map(fn ($c) => ['type' => 'communication', 'icon' => 'fa-comments', 'color' => 'info', 'title' => ucfirst($c->type).($c->subject ? ': '.$c->subject : ''), 'body' => $c->body, 'date' => $c->occurred_at]));
        $timeline->push($customer->follow_ups()->latest('due_date')->limit(20)->get()->map(fn ($f) => ['type' => 'follow_up', 'icon' => 'fa-calendar-check', 'color' => $f->completed_at ? 'success' : 'warning', 'title' => ucfirst($f->type).($f->completed_at ? ' (Done)' : ''), 'body' => $f->note, 'date' => $f->due_date]));
        $timeline->push($customer->documents->map(fn ($d) => ['type' => 'document', 'icon' => 'fa-file-alt', 'color' => 'secondary', 'title' => $d->title.' (v'.$d->version.')', 'body' => $d->original_name, 'date' => $d->created_at]));
        $timeline = $timeline->flatten(1)->sortByDesc('date')->take(50);

        return view('customers.show', compact('customer', 'timeline', 'invoices', 'payments', 'totalInvoiced', 'totalPaid', 'outstanding'));
    }

    public function create()
    {
        $this->authorize('create-customers');
        $relations['currency_id'] = \App\Models\Currency::where('is_active', true)->pluck('code', 'id')->toArray();
        return view('customers.create', ['relations' => $relations]);
    }

    public function store(StoreCustomerRequest $request)
    {
        $this->authorize('create-customers');

        $this->service->create($request->validated());

        return redirect()->route('customers.index')->with('success', 'Created successfully.');
    }

    public function edit(Customer $customer)
    {
        $this->authorize('update-customers');
        $relations['currency_id'] = \App\Models\Currency::where('is_active', true)->pluck('code', 'id')->toArray();
        return view('customers.edit', ['customer' => $customer, 'relations' => $relations]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $this->authorize('update-customers');

        $this->service->update($customer->id, $request->validated());

        return redirect()->route('customers.index')->with('success', 'Updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $this->authorize('delete-customers');

        $this->service->delete($customer->id);

        return redirect()->route('customers.index')->with('success', 'Deleted successfully.');
    }
}
