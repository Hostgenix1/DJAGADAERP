<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function index()
    {
        $this->authorize('view-employees');

        $total = Employee::count();
        $active = Employee::where('is_active', true)->count();
        $monthlyPayroll = Employee::where('is_active', true)->sum('base_salary');
        $defaultCurrency = \App\Models\Currency::where('is_default', true)->first();

        return view('employees.index', compact('total', 'active', 'monthlyPayroll', 'defaultCurrency'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-employees');

        return DataTables::eloquent(Employee::query()->with('currency'))
            ->addIndexColumn()
            ->editColumn('hire_date', fn (Employee $e) => $e->hire_date?->format('d M Y'))
            ->editColumn('base_salary', fn (Employee $e) => $e->base_salary)
            ->editColumn('currency_id', fn (Employee $e) => $e->currency?->code ?? '-')
            ->editColumn('is_active', fn (Employee $e) => $e->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>')
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->addColumn('actions', fn (Employee $e) => view('employees.partials.actions', ['row' => $e])->render())
            ->rawColumns(['is_active', 'actions'])
            ->make(true);
    }

    public function show(Employee $employee)
    {
        $this->authorize('view-employees');

        $employee->load(['currency', 'documents', 'user', 'payrollEntries' => fn ($q) => $q->orderByDesc('period')->limit(24)]);
        $employee->loadSum('payrollEntries as total_net_paid', 'net_salary');

        $leaves = $employee->leaves()->orderByDesc('start_date')->limit(12)->get();
        $attendanceThisMonth = (int) \App\Models\Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->where('status', 'present')->count();

        return view('employees.show', compact('employee', 'leaves', 'attendanceThisMonth'));
    }

    public function create()
    {
        $this->authorize('create-employees');

        $currencies = \App\Models\Currency::where('is_active', true)->pluck('code', 'id');
        $users = \App\Models\User::orderBy('name')->pluck('name', 'id');

        return view('employees.create', compact('currencies', 'users'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-employees');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date|before:today',
            'nationality' => 'nullable|string|max:100',
            'emirates_id' => 'nullable|string|max:50',
            'labour_card_no' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'base_salary' => 'required|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'iban' => 'nullable|string|max:34',
            'user_id' => 'nullable|exists:users,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'is_active' => 'nullable',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        Employee::create($data);

        return redirect()->route('employees.index')->with('success', 'Employee created.');
    }

    public function edit(Employee $employee)
    {
        $this->authorize('update-employees');

        $currencies = \App\Models\Currency::where('is_active', true)->pluck('code', 'id');
        $users = \App\Models\User::orderBy('name')->pluck('name', 'id');

        return view('employees.edit', compact('employee', 'currencies', 'users'));
    }

    public function update(Request $request, Employee $employee)
    {
        $this->authorize('update-employees');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date|before:today',
            'nationality' => 'nullable|string|max:100',
            'emirates_id' => 'nullable|string|max:50',
            'labour_card_no' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'base_salary' => 'required|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'iban' => 'nullable|string|max:34',
            'user_id' => 'nullable|exists:users,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'is_active' => 'nullable',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Employee updated.');
    }

    public function destroy(Employee $employee)
    {
        $this->authorize('delete-employees');

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted.');
    }
}