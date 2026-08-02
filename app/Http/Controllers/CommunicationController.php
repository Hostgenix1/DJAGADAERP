<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommunicationRequest;
use App\Http\Requests\UpdateCommunicationRequest;
use App\Models\Communication;
use App\Services\CommunicationService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CommunicationController extends Controller
{
    public function __construct(protected CommunicationService $service)
    {
    }

    public function index()
    {
        $this->authorize('view-communications');

        return view('communications.index', ['columns' => array (
  0 => 
  array (
    'label' => 'Type',
    'data' => 'type',
  ),
  1 => 
  array (
    'label' => 'Direction',
    'data' => 'direction',
  ),
  2 => 
  array (
    'label' => 'Subject',
    'data' => 'subject',
  ),
  3 => 
  array (
    'label' => 'Date/Time',
    'data' => 'occurred_at',
  ),
)]);
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-communications');

        return DataTables::eloquent($this->service->query())
            ->addIndexColumn()
            ->addColumn('actions', function (Communication $row) {
                return view('communications.partials.actions', ['row' => $row])->render();
            })
            ->editColumn('occurred_at', fn ($m) => $m->occurred_at?->format('d M Y H:i'))
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-communications');
    $relations['contact_id'] = \App\Models\Contact::pluck('full_name', 'id');
    $relations['user_id'] = \App\Models\User::pluck('name', 'id');
        return view('communications.create', ['relations' => $relations]);
    }

    public function store(StoreCommunicationRequest $request)
    {
        $this->authorize('create-communications');

        $this->service->create($request->validated());

        return redirect()->route('communications.index')->with('success', 'Created successfully.');
    }

    public function edit(Communication $communication)
    {
        $this->authorize('update-communications');
    $relations['contact_id'] = \App\Models\Contact::pluck('full_name', 'id');
    $relations['user_id'] = \App\Models\User::pluck('name', 'id');
        return view('communications.edit', ['communication' => $communication, 'relations' => $relations]);
    }

    public function update(UpdateCommunicationRequest $request, Communication $communication)
    {
        $this->authorize('update-communications');

        $this->service->update($communication->id, $request->validated());

        return redirect()->route('communications.index')->with('success', 'Updated successfully.');
    }

    public function destroy(Communication $communication)
    {
        $this->authorize('delete-communications');

        $this->service->delete($communication->id);

        return redirect()->route('communications.index')->with('success', 'Deleted successfully.');
    }
}
