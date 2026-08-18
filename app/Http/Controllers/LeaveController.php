<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LeaveController extends Controller
{
    public function index()
    {
        $this->authorize('view-leaves');

        $pending = Leave::where('status', 'pending')->count();
        $approved = Leave::where('status', 'approved')->count();

        return view('leaves.index', compact('pending', 'approved'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-leaves');

        return DataTables::eloquent(Leave::query()->with('employee'))
            ->addIndexColumn()
            ->editColumn('employee_id', fn (Leave $l) => $l->employee?->name ?? '-')
            ->editColumn('start_date', fn (Leave $l) => $l->start_date?->format('d M Y'))
            ->editColumn('end_date', fn (Leave $l) => $l->end_date?->format('d M Y'))
            ->editColumn('type', fn (Leave $l) => ucfirst($l->type))
            ->editColumn('status', fn (Leave $l) => '<span class="badge '.match($l->status) {
                'approved' => 'badge-success',
                'rejected' => 'badge-danger',
                default => 'badge-warning',
            }.'">'.ucfirst($l->status).'</span>')
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->addColumn('actions', fn (Leave $l) => view('leaves.partials.actions', ['row' => $l])->render())
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-leaves');

        $employees = \App\Models\Employee::where('is_active', true)->orderBy('name')->pluck('name', 'id');

        return view('leaves.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-leaves');

        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:annual,sick,unpaid,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days' => 'required|numeric|min:0.5',
            'reason' => 'nullable|string|max:500',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        Leave::create($data);

        return redirect()->route('leaves.index')->with('success', 'Leave request created.');
    }

    public function edit(Leave $leave)
    {
        $this->authorize('update-leaves');

        $employees = \App\Models\Employee::where('is_active', true)->orderBy('name')->pluck('name', 'id');

        return view('leaves.edit', compact('leave', 'employees'));
    }

    public function update(Request $request, Leave $leave)
    {
        $this->authorize('update-leaves');

        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|in:annual,sick,unpaid,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days' => 'required|numeric|min:0.5',
            'reason' => 'nullable|string|max:500',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $leave->update($data);

        return redirect()->route('leaves.index')->with('success', 'Leave request updated.');
    }

    public function destroy(Leave $leave)
    {
        $this->authorize('delete-leaves');

        $leave->delete();

        return redirect()->route('leaves.index')->with('success', 'Leave request deleted.');
    }
}