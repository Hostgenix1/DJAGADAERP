<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PayrollEntry;
use App\Services\PayrollService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PayrollController extends Controller
{
    public function index()
    {
        $this->authorize('view-payroll');

        $period = request('period', now()->format('Y-m'));

        $entries = PayrollEntry::where('period', $period)->with('employee');
        $totalGross = (clone $entries)->sum('gross_salary');
        $totalNet = (clone $entries)->sum('net_salary');
        $dueCount = (clone $entries)->where('status', PayrollEntry::STATUS_DUE)->count();
        $defaultCurrency = \App\Models\Currency::where('is_default', true)->first();

        $periods = PayrollEntry::query()->select('period')->distinct()->orderByDesc('period')->pluck('period');

        return view('payroll.index', compact('period', 'totalGross', 'totalNet', 'dueCount', 'defaultCurrency', 'periods'));
    }

    public function datatable(Request $request)
    {
        $this->authorize('view-payroll');

        $query = PayrollEntry::query()->with(['employee', 'currency']);

        if ($request->filled('period')) {
            $query->where('period', $request->period);
        }

        $badges = [
            PayrollEntry::STATUS_DRAFT => 'badge-secondary',
            PayrollEntry::STATUS_APPROVED => 'badge-info',
            PayrollEntry::STATUS_DUE => 'badge-warning',
            PayrollEntry::STATUS_PAID => 'badge-success',
        ];

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('employee_id', fn (PayrollEntry $p) => e($p->employee?->name ?? '-'))
            ->editColumn('gross_salary', fn (PayrollEntry $p) => number_format((float) $p->gross_salary, 2))
            ->editColumn('allowances', fn (PayrollEntry $p) => number_format((float) $p->allowances, 2))
            ->editColumn('deductions', fn (PayrollEntry $p) => number_format((float) $p->deductions, 2))
            ->editColumn('net_salary', fn (PayrollEntry $p) => '<strong>'.number_format((float) $p->net_salary, 2).'</strong>')
            ->editColumn('currency_id', fn (PayrollEntry $p) => e($p->currency?->code ?? '-'))
            ->editColumn('status', fn (PayrollEntry $p) => '<span class="badge '.($badges[$p->status] ?? 'badge-secondary').'">'.ucfirst($p->status).'</span>'.($p->payslip_emailed_at ? ' <i class="fas fa-envelope text-success" title="Payslip emailed '.e($p->payslip_emailed_at->format('d M Y H:i')).'"></i>' : ''))
            ->editColumn('pay_date', fn (PayrollEntry $p) => $p->pay_date?->format('d M Y') ?? '-')
            ->editColumn('paid_on', fn (PayrollEntry $p) => $p->paid_on?->format('d M Y') ?? ($p->pay_date ? $p->pay_date->format('d M Y').' (sched.)' : '-'))
            ->editColumn('created_at', fn ($m) => $m->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->addColumn('actions', fn (PayrollEntry $p) => view('payroll.partials.actions', ['row' => $p])->render())
            ->rawColumns(['net_salary', 'status', 'actions'])
            ->make(true);
    }

    /**
     * JSON auto-fill for the payroll form: attendance + salary math.
     */
    public function autofill(Request $request)
    {
        $this->authorize('create-payroll');

        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period' => 'required|regex:/^\d{4}-\d{2}$/',
        ]);

        $employee = Employee::findOrFail($data['employee_id']);

        return response()->json(PayrollService::autofill($employee, $data['period']));
    }

    public function create()
    {
        $this->authorize('create-payroll');

        $employees = Employee::where('is_active', true)->orderBy('name')->get(['id', 'name', 'base_salary', 'transport_allowance', 'currency_id']);
        $currencies = \App\Models\Currency::where('is_active', true)->pluck('code', 'id');

        return view('payroll.create', compact('employees', 'currencies'));
    }

    protected function validatedBreakdown(Request $request): array
    {
        return $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period' => 'required|regex:/^\d{4}-\d{2}$/',
            'base_salary' => 'required|numeric|min:0',
            'working_days' => 'nullable|integer|min:1|max:31',
            'present_days' => 'nullable|numeric|min:0',
            'absent_days' => 'nullable|numeric|min:0',
            'leave_days' => 'nullable|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'overtime_amount' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'absence_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'currency_id' => 'nullable|exists:currencies,id',
            'status' => 'required|in:draft,approved,due,paid',
            'pay_date' => 'nullable|date|after_or_equal:today',
            'paid_on' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string|max:500',
        ]);
    }

    protected function applyCalculation(array $data, Employee $employee): array
    {
        $data['working_days'] = $data['working_days'] ?? PayrollService::DAYS_DIVISOR_DEFAULT;
        $data['present_days'] = $data['present_days'] ?? 0;
        $data['absent_days'] = $data['absent_days'] ?? 0;
        $data['leave_days'] = $data['leave_days'] ?? 0;
        $data['overtime_hours'] = $data['overtime_hours'] ?? 0;
        $data['overtime_amount'] = $data['overtime_amount'] ?? 0;
        $data['transport_allowance'] = $data['transport_allowance'] ?? 0;
        $data['bonus'] = $data['bonus'] ?? 0;
        $data['other_allowances'] = $data['other_allowances'] ?? 0;
        $data['other_deductions'] = $data['other_deductions'] ?? 0;

        $calc = PayrollService::calculate(
            (float) $data['base_salary'],
            (int) $data['working_days'],
            (float) $data['absent_days'],
            (float) $data['transport_allowance'],
            (float) $data['bonus'],
            (float) $data['other_allowances'],
            (float) $data['other_deductions'],
            isset($data['absence_deduction']) ? (float) $data['absence_deduction'] : null,
            (float) $data['overtime_amount'],
        );

        // Server is the source of truth: always recompute totals.
        $data['absence_deduction'] = $calc['absenceDeduction'];
        $data['gross_salary'] = $data['base_salary'];
        $data['allowances'] = $calc['allowances'];
        $data['deductions'] = $calc['deductions'];
        $data['net_salary'] = $calc['net'];
        $data['currency_id'] = $data['currency_id'] ?? $employee->currency_id;

        return $data;
    }

    public function store(Request $request)
    {
        $this->authorize('create-payroll');

        $data = $this->validatedBreakdown($request);
        $employee = Employee::findOrFail($data['employee_id']);
        $data = $this->applyCalculation($data, $employee);

        PayrollEntry::create($data);

        return redirect()->route('payroll.index', ['period' => $data['period']])
            ->with('success', 'Payroll entry created. Net: '.number_format($data['net_salary'], 2));
    }

    public function edit(PayrollEntry $payrollEntry)
    {
        $this->authorize('update-payroll');

        if (in_array($payrollEntry->status, [PayrollEntry::STATUS_PAID], true)) {
            return redirect()->route('payroll.index')->with('error', 'Paid entries cannot be edited. Delete and recreate if needed.');
        }

        $employees = Employee::where('is_active', true)->orderBy('name')->get(['id', 'name', 'base_salary', 'transport_allowance', 'currency_id']);
        $currencies = \App\Models\Currency::where('is_active', true)->pluck('code', 'id');

        return view('payroll.edit', compact('payrollEntry', 'employees', 'currencies'));
    }

    public function update(Request $request, PayrollEntry $payrollEntry)
    {
        $this->authorize('update-payroll');

        if ($payrollEntry->status === PayrollEntry::STATUS_PAID) {
            return redirect()->route('payroll.index')->with('error', 'Paid entries cannot be edited.');
        }

        $data = $this->validatedBreakdown($request);
        $employee = Employee::findOrFail($data['employee_id']);
        $data = $this->applyCalculation($data, $employee);

        $payrollEntry->update($data);

        return redirect()->route('payroll.index', ['period' => $data['period']])
            ->with('success', 'Payroll entry updated. Net: '.number_format($data['net_salary'], 2));
    }

    public function destroy(PayrollEntry $payrollEntry)
    {
        $this->authorize('delete-payroll');

        if ($payrollEntry->status === PayrollEntry::STATUS_PAID) {
            return redirect()->route('payroll.index')->with('error', 'Paid entries cannot be deleted.');
        }

        $payrollEntry->delete();

        return redirect()->route('payroll.index')->with('success', 'Payroll entry deleted.');
    }

    /**
     * Mark due/approved entry as paid + email the payslip.
     */
    public function markPaid(Request $request, PayrollEntry $payrollEntry)
    {
        $this->authorize('update-payroll');

        if ($payrollEntry->status === PayrollEntry::STATUS_PAID) {
            return redirect()->route('payroll.index')->with('error', 'Already marked as paid.');
        }

        $data = $request->validate(['paid_on' => 'nullable|date|after_or_equal:today']);

        $payrollEntry->update([
            'status' => PayrollEntry::STATUS_PAID,
            'paid_on' => $data['paid_on'] ?? $payrollEntry->pay_date ?? today(),
        ]);

        // Sync with the rest of the system: cash outflow -> payroll expense (idempotent).
        $expense = PayrollService::postSalaryExpense($payrollEntry->fresh());

        $emailed = PayrollService::emailPayslip($payrollEntry->fresh());

        $msg = 'Marked as paid.';
        $msg .= $expense ? ' Salary expense posted.' : '';
        $msg .= $emailed ? ' Payslip emailed to '.$payrollEntry->employee?->email.'.'
            : ($payrollEntry->employee?->email ? ' WARNING: payslip email could not be sent — check mail settings.' : ' No employee email on file — payslip not emailed.');

        return redirect()->route('payroll.index', ['period' => $payrollEntry->period])->with($emailed ? 'success' : 'warning', $msg);
    }

    /**
     * Bank salary-transfer file (CSV) for the selected period.
     */
    public function export(Request $request)
    {
        $this->authorize('view-payroll');

        $period = $request->validate(['period' => 'required|regex:/^\d{4}-\d{2}$/'])['period'];

        $entries = PayrollEntry::with(['employee', 'currency'])
            ->where('period', $period)
            ->whereIn('status', [PayrollEntry::STATUS_APPROVED, PayrollEntry::STATUS_DUE, PayrollEntry::STATUS_PAID])
            ->orderBy('id')
            ->get();

        $lines = [];
        $lines[] = 'Employee Name,IBAN,Amount,Currency,Period,Pay Date,Status,Reference';
        foreach ($entries as $e) {
            $lines[] = implode(',', [
                '"'.str_replace('"', '""', $e->employee?->name ?? '').'"',
                '"'.str_replace('"', '""', $e->employee?->iban ?? '').'"',
                number_format((float) $e->net_salary, 2, '.', ''),
                $e->currency?->code ?? '',
                $e->period,
                $e->pay_date?->format('Y-m-d') ?? '',
                $e->status,
                'SAL-'.$e->period.'-'.$e->id,
            ]);
        }

        return response(implode("\n", $lines), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="salary-transfer-'.$period.'.csv"',
        ]);
    }

    public function payslip(PayrollEntry $payrollEntry)
    {
        $this->authorize('view-payroll');

        $pdf = PayrollService::renderPayslip($payrollEntry);

        return $pdf->stream('payslip-'.$payrollEntry->employee->name.'-'.$payrollEntry->period.'.pdf');
    }
}
