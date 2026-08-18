<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AttendanceController extends Controller
{
    public function index()
    {
        $this->authorize('view-attendance');

        $today = Attendance::whereDate('date', now()->toDateString())->count();
        $present = Attendance::whereDate('date', now()->toDateString())->where('status', 'present')->count();
        $leave = Attendance::whereDate('date', now()->toDateString())->where('status', 'leave')->count();

        return view('attendance.index', compact('today', 'present', 'leave'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-attendance');

        return DataTables::eloquent(Attendance::query()->with('employee'))
            ->addIndexColumn()
            ->editColumn('employee_id', fn (Attendance $a) => $a->employee?->name ?? '-')
            ->editColumn('date', fn (Attendance $a) => $a->date?->format('d M Y'))
            ->editColumn('status', fn (Attendance $a) => '<span class="badge '.match($a->status) {
                'present' => 'badge-success',
                'absent' => 'badge-danger',
                'leave' => 'badge-warning',
                'half_day' => 'badge-info',
                default => 'badge-secondary',
            }.'">'.ucfirst(str_replace('_', ' ', $a->status)).'</span>')
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->addColumn('actions', fn (Attendance $a) => view('attendance.partials.actions', ['row' => $a])->render())
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-attendance');

        $employees = \App\Models\Employee::where('is_active', true)->orderBy('name')->pluck('name', 'id');

        return view('attendance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-attendance');

        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'required|in:present,absent,leave,half_day',
            'notes' => 'nullable|string|max:500',
        ]);

        Attendance::updateOrCreate(
            ['employee_id' => $data['employee_id'], 'date' => $data['date']],
            $data
        );

        return redirect()->route('attendance.index')->with('success', 'Attendance recorded.');
    }

    public function edit(Attendance $attendance)
    {
        $this->authorize('update-attendance');

        $employees = \App\Models\Employee::where('is_active', true)->orderBy('name')->pluck('name', 'id');

        return view('attendance.edit', compact('attendance', 'employees'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $this->authorize('update-attendance');

        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'required|in:present,absent,leave,half_day',
            'notes' => 'nullable|string|max:500',
        ]);

        $attendance->update($data);

        return redirect()->route('attendance.index')->with('success', 'Attendance updated.');
    }

    public function destroy(Attendance $attendance)
    {
        $this->authorize('delete-attendance');

        $attendance->delete();

        return redirect()->route('attendance.index')->with('success', 'Attendance deleted.');
    }
}