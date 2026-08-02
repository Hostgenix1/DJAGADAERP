<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
    public function __construct(protected ContactService $service)
    {
    }

    public function index()
    {
        $this->authorize('view-contacts');

        return view('contacts.index', ['columns' => array (
  0 => 
  array (
    'label' => 'Full Name',
    'data' => 'full_name',
  ),
  1 => 
  array (
    'label' => 'Email',
    'data' => 'email',
  ),
  2 => 
  array (
    'label' => 'Phone',
    'data' => 'phone',
  ),
  3 => 
  array (
    'label' => 'Position',
    'data' => 'position',
  ),
  4 => 
  array (
    'label' => 'Primary Contact',
    'data' => 'is_primary',
  ),
)]);
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-contacts');

        return DataTables::eloquent($this->service->query())
            ->addIndexColumn()
            ->addColumn('actions', function (Contact $row) {
                return view('contacts.partials.actions', ['row' => $row])->render();
            })
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-contacts');
    $relations['customer_id'] = \App\Models\Customer::pluck('company_name', 'id');
        return view('contacts.create', ['relations' => $relations]);
    }

    public function store(StoreContactRequest $request)
    {
        $this->authorize('create-contacts');

        $this->service->create($request->validated());

        return redirect()->route('contacts.index')->with('success', 'Created successfully.');
    }

    public function edit(Contact $contact)
    {
        $this->authorize('update-contacts');
    $relations['customer_id'] = \App\Models\Customer::pluck('company_name', 'id');
        return view('contacts.edit', ['contact' => $contact, 'relations' => $relations]);
    }

    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $this->authorize('update-contacts');

        $this->service->update($contact->id, $request->validated());

        return redirect()->route('contacts.index')->with('success', 'Updated successfully.');
    }

    public function destroy(Contact $contact)
    {
        $this->authorize('delete-contacts');

        $this->service->delete($contact->id);

        return redirect()->route('contacts.index')->with('success', 'Deleted successfully.');
    }
}
