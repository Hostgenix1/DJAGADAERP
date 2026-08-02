<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Lead;
use App\Services\LeadService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LeadController extends Controller
{
    public function __construct(protected LeadService $service)
    {
    }

    public function index()
    {
        $this->authorize('view-leads');

        return view('leads.index', ['columns' => array (
  0 => 
  array (
    'label' => 'Company Name',
    'data' => 'company_name',
  ),
  1 => 
  array (
    'label' => 'Contact Name',
    'data' => 'contact_name',
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
    'label' => 'Source',
    'data' => 'source',
  ),
  5 => 
  array (
    'label' => 'Status',
    'data' => 'status',
  ),
  6 => 
  array (
    'label' => 'Expected Amount',
    'data' => 'expected_amount',
  ),
  7 => 
  array (
    'label' => 'Expected Close Date',
    'data' => 'expected_date',
  ),
)]);
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-leads');

        return DataTables::eloquent($this->service->query())
            ->addIndexColumn()
            ->addColumn('actions', function (Lead $row) {
                return view('leads.partials.actions', ['row' => $row])->render();
            })
            ->editColumn('expected_date', fn ($m) => $m->expected_date?->format('d M Y'))
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function show(Lead $lead)
    {
        $this->authorize('view-leads');
        $lead->load(['currency', 'owner', 'customer']);
        return view('leads.show', compact('lead'));
    }

    public function create()
    {
        $this->authorize('create-leads');
    $relations['currency_id'] = \App\Models\Currency::pluck('code', 'id');
    $relations['owner_id'] = \App\Models\User::pluck('name', 'id');
    $relations['customer_id'] = \App\Models\Customer::pluck('company_name', 'id');
        return view('leads.create', ['relations' => $relations]);
    }

    public function store(StoreLeadRequest $request)
    {
        $this->authorize('create-leads');

        $this->service->create($request->validated());

        return redirect()->route('leads.index')->with('success', 'Created successfully.');
    }

    public function edit(Lead $lead)
    {
        $this->authorize('update-leads');
    $relations['currency_id'] = \App\Models\Currency::pluck('code', 'id');
    $relations['owner_id'] = \App\Models\User::pluck('name', 'id');
    $relations['customer_id'] = \App\Models\Customer::pluck('company_name', 'id');
        return view('leads.edit', ['lead' => $lead, 'relations' => $relations]);
    }

    public function update(UpdateLeadRequest $request, Lead $lead)
    {
        $this->authorize('update-leads');

        $this->service->update($lead->id, $request->validated());

        return redirect()->route('leads.index')->with('success', 'Updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $this->authorize('delete-leads');

        $this->service->delete($lead->id);

        return redirect()->route('leads.index')->with('success', 'Deleted successfully.');
    }
}
