<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFollowUpRequest;
use App\Http\Requests\UpdateFollowUpRequest;
use App\Models\FollowUp;
use App\Services\FollowUpService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FollowUpController extends Controller
{
    public function __construct(protected FollowUpService $service)
    {
    }

    public function index()
    {
        $this->authorize('view-follow-ups');

        return view('follow_ups.index', ['columns' => array (
  0 =>
  array (
    'label' => 'Type',
    'data' => 'type',
  ),
  1 =>
  array (
    'label' => 'Due Date',
    'data' => 'due_date',
  ),
)]);
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-follow-ups');

        return DataTables::eloquent($this->service->query())
            ->addIndexColumn()
            ->addColumn('actions', function (FollowUp $row) {
                return view('follow_ups.partials.actions', ['row' => $row])->render();
            })
            ->editColumn('due_date', fn ($m) => $m->due_date?->format('d M Y'))
            ->editColumn('completed_at', fn ($m) => $m->completed_at?->format('d M Y H:i'))
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-follow-ups');
    $relations['assigned_to'] = \App\Models\User::pluck('name', 'id');
        return view('follow_ups.create', ['relations' => $relations]);
    }

    public function store(StoreFollowUpRequest $request)
    {
        $this->authorize('create-follow-ups');

        $this->service->create($request->validated());

        return redirect()->route('follow_ups.index')->with('success', 'Created successfully.');
    }

    public function edit(FollowUp $follow_up)
    {
        $this->authorize('update-follow-ups');
    $relations['assigned_to'] = \App\Models\User::pluck('name', 'id');
        return view('follow_ups.edit', ['follow_up' => $follow_up, 'relations' => $relations]);
    }

    public function update(UpdateFollowUpRequest $request, FollowUp $follow_up)
    {
        $this->authorize('update-follow-ups');

        $this->service->update($follow_up->id, $request->validated());

        return redirect()->route('follow_ups.index')->with('success', 'Updated successfully.');
    }

    public function destroy(FollowUp $follow_up)
    {
        $this->authorize('delete-follow-ups');

        $this->service->delete($follow_up->id);

        return redirect()->route('follow_ups.index')->with('success', 'Deleted successfully.');
    }
}
