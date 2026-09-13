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

        // HR / Admin (full payroll access) sees EVERYONE's payslips.
        if (auth()->user()->can('view-payroll')) {
            $employees = Employee::orderBy('name')->pluck('name', 'id');
            $query = PayrollEntry::with(['employee', 'currency'])->orderByDesc('period')->orderBy('employee_id');
            if (request()->filled('employee_id')) {
                $query->where('employee_id', request('employee_id'));
            }
            $entries = $query->limit(200)->get();

            return view('my_payslips.index', ['employee' => null, 'entries' => $entries, 'employees' => $employees, 'isHrView' => true]);
        }

        $employee = $this->myEmployee();

        $entries = $employee
            ? PayrollEntry::with('currency')->where('employee_id', $employee->id)->orderByDesc('period')->get()
            : collect();

        return view('my_payslips.index', ['employee' => $employee, 'entries' => $entries, 'employees' => [], 'isHrView' => false]);
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

        $pdf = PayrollService::renderPayslip($payrollEntry);

        return $pdf->download('payslip-'.$payrollEntry->period.'.pdf');
    }
}
