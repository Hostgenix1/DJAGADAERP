<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PayrollEntry;
use App\Services\PayrollService;

class MyPayslipController extends Controller
{
    protected function myEmployee(): ?Employee
    {
        return Employee::where('user_id', auth()->id())->first();
    }

    public function index()
    {
        $this->authorize('view-own-payslips');

        $periods = PayrollEntry::query()->select('period')->distinct()->orderByDesc('period')->pluck('period');
        $period = request('period');

        // HR / Admin (full payroll access) sees EVERYONE's payslips.
        if (auth()->user()->can('view-payroll')) {
            $employees = Employee::orderBy('name')->pluck('name', 'id');
            $query = PayrollEntry::with(['employee', 'currency'])->orderByDesc('period')->orderBy('employee_id');
            if (request()->filled('employee_id')) {
                $query->where('employee_id', request('employee_id'));
            }
            if (filled($period)) {
                $query->where('period', $period);
            }
            $entries = $query->limit(200)->get();

            return view('my_payslips.index', [
                'employee' => null, 'entries' => $entries, 'employees' => $employees, 'isHrView' => true,
                'periods' => $periods, 'period' => $period,
            ]);
        }

        $employee = $this->myEmployee();

        $query = $employee
            ? PayrollEntry::with('currency')->where('employee_id', $employee->id)
            : null;

        if ($query && filled($period)) {
            $query->where('period', $period);
        }

        $entries = $query ? $query->orderByDesc('period')->get() : collect();

        return view('my_payslips.index', [
            'employee' => $employee, 'entries' => $entries, 'employees' => [], 'isHrView' => false,
            'periods' => $periods, 'period' => $period,
        ]);
    }

    public function download(PayrollEntry $payrollEntry)
    {
        $this->authorize('view-own-payslips');

        // HR / Admin may download anyone's payslip.
        if (! auth()->user()->can('view-payroll')) {
            $employee = $this->myEmployee();
            if (! $employee || $payrollEntry->employee_id !== $employee->id) {
                abort(403, 'You can only view your own payslips.');
            }
        }

        if (! in_array($payrollEntry->status, [PayrollEntry::STATUS_DUE, PayrollEntry::STATUS_PAID], true)) {
            abort(403, 'Payslip is available after HR finalises your salary.');
        }

        return response(PayrollService::payslipPdf($payrollEntry), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="payslip-'.$payrollEntry->period.'.pdf"',
        ]);
    }
}
