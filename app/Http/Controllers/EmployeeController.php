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

    public function create()
    {
        $this->authorize('create-employees');

        $currencies = \App\Models\Currency::where('is_active', true)->pluck('code', 'id');

        return view('employees.create', compact('currencies'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-employees');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'base_salary' => 'required|numeric|min:0',
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

        return view('employees.edit', compact('employee', 'currencies'));
    }

    public function update(Request $request, Employee $employee)
    {
        $this->authorize('update-employees');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'base_salary' => 'required|numeric|min:0',
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