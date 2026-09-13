<?php

namespace App\Services;

use App\Mail\PayslipMailable;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\PayrollEntry;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PayrollService
{
    public const DAYS_DIVISOR_DEFAULT = 30;

    /**
     * Summarise attendance for an employee + month (period format Y-m).
     * Approved leave days are counted from leave records overlapping the month.
     */
    public static function attendanceSummary(Employee $employee, string $period): array
    {
        $start = Carbon::createFromFormat('Y-m', $period)->startOfMonth();
        $end = (clone $start)->endOfMonth();

        $present = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->where('status', 'present')
            ->count();

        $absent = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->where('status', 'absent')
            ->count();

        $leaveDays = 0;
        $leaves = Leave::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $end->toDateString())
            ->whereDate('end_date', '>=', $start->toDateString())
            ->get();
        foreach ($leaves as $leave) {
            $from = max($start->toDateString(), $leave->start_date->toDateString());
            $to = min($end->toDateString(), $leave->end_date->toDateString());
            $leaveDays += Carbon::parse($from)->diffInDays(Carbon::parse($to)) + 1;
        }

        return [
            'present_days' => $present,
            'absent_days' => $absent,
            'leave_days' => $leaveDays,
            'calendar_days' => $start->daysInMonth,
        ];
    }

    /**
     * Full salary math. Returns every component + totals.
     */
    public static function calculate(
        float $baseSalary,
        int $workingDays,
        float $absentDays,
        float $transportAllowance,
        float $bonus,
        float $otherAllowances,
        float $otherDeductions,
        ?float $absenceDeductionOverride = null,
    ): array {
        $workingDays = $workingDays > 0 ? $workingDays : self::DAYS_DIVISOR_DEFAULT;
        $perDay = round($baseSalary / $workingDays, 2);
        $absenceDeduction = $absenceDeductionOverride ?? round($perDay * $absentDays, 2);

        $allowances = round($transportAllowance + $bonus + $otherAllowances, 2);
        $deductions = round($absenceDeduction + $otherDeductions, 2);
        $net = round(($baseSalary + $allowances) - $deductions, 2);

        return compact('perDay', 'absenceDeduction', 'allowances', 'deductions', 'net');
    }

    /**
     * Auto-fill array for the payroll form: attendance + employee defaults.
     */
    public static function autofill(Employee $employee, string $period): array
    {
        $summary = self::attendanceSummary($employee, $period);
        $base = (float) $employee->base_salary;
        $transport = (float) ($employee->transport_allowance ?? 0);
        $calc = self::calculate($base, self::DAYS_DIVISOR_DEFAULT, (float) $summary['absent_days'], $transport, 0, 0, 0);

        return [
            'base_salary' => $base,
            'working_days' => self::DAYS_DIVISOR_DEFAULT,
            'present_days' => $summary['present_days'],
            'absent_days' => $summary['absent_days'],
            'leave_days' => $summary['leave_days'],
            'calendar_days' => $summary['calendar_days'],
            'transport_allowance' => $transport,
            'bonus' => 0,
            'other_allowances' => 0,
            'absence_deduction' => $calc['absenceDeduction'],
            'other_deductions' => 0,
            'per_day' => $calc['perDay'],
            'allowances' => $calc['allowances'],
            'deductions' => $calc['deductions'],
            'net_salary' => $calc['net'],
            'currency_id' => $employee->currency_id,
        ];
    }

    /**
     * Flip approved entries whose pay date has arrived to "due".
     * Returns number of entries updated.
     */
    public static function markDue(?string $asOf = null): int
    {
        $asOf = $asOf ?? today()->toDateString();

        return PayrollEntry::where('status', PayrollEntry::STATUS_APPROVED)
            ->whereNotNull('pay_date')
            ->whereDate('pay_date', '<=', $asOf)
            ->update(['status' => PayrollEntry::STATUS_DUE]);
    }

    /**
     * Post the paid salary as a payroll-category expense (cash outflow).
     * Idempotent: returns existing expense if already posted for this entry.
     */
    public static function postSalaryExpense(PayrollEntry $payrollEntry): ?\App\Models\Expense
    {
        $marker = 'PAYROLL:'.$payrollEntry->id;

        $existing = \App\Models\Expense::where('category', 'payroll')
            ->where('notes', 'like', '%'.$marker.'%')
            ->first();
        if ($existing) {
            return $existing;
        }

        if ((float) $payrollEntry->net_salary == 0.0) {
            return null;
        }

        return \App\Models\Expense::create([
            'expense_date' => $payrollEntry->paid_on?->toDateString() ?? today()->toDateString(),
            'amount' => $payrollEntry->net_salary,
            'currency_id' => $payrollEntry->currency_id,
            'category' => 'payroll',
            'description' => 'Salary '.$payrollEntry->period.' — '.($payrollEntry->employee?->name ?? ''),
            'paid_to' => $payrollEntry->employee?->name,
            'notes' => $marker.' auto-posted on salary payment',
        ]);
    }

    /**
     */
    public static function renderPayslip(PayrollEntry $payrollEntry)
    {
        $payrollEntry->load(['employee', 'employee.currency', 'currency']);

        $svc = app(SettingsService::class);
        $logoPath = $svc->get('company_logo');
        $logoBase64 = null;
        if ($logoPath && Storage::disk('public')->exists($logoPath)) {
            $fullPath = Storage::disk('public')->path($logoPath);
            $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
            $mime = match ($ext) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml',
                default => 'image/png',
            };
            $logoBase64 = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($fullPath));
        }

        $company = [
            'name' => $svc->get('company_name'),
            'address' => $svc->get('company_address'),
            'city' => $svc->get('company_city'),
            'country' => $svc->get('company_country'),
            'email' => $svc->get('company_email'),
            'phone' => $svc->get('company_phone'),
            'trn' => $svc->get('company_trn'),
            'show_logo' => $svc->get('show_logo_on_docs'),
            'logo_url' => $logoBase64,
        ];

        return Pdf::loadView('payroll.payslip', compact('payrollEntry', 'company'))->setPaper('a4');
    }

    /**
     * Email the payslip PDF to the employee. Returns true on success.
     * Never throws — failures return false so payroll can still be marked paid.
     */
    public static function emailPayslip(PayrollEntry $payrollEntry): bool
    {
        $email = $payrollEntry->employee?->email;
        if (! $email) {
            return false;
        }

        try {
            Mail::to($email)->send(new PayslipMailable($payrollEntry));
            $payrollEntry->update(['payslip_emailed_at' => now()]);

            return true;
        } catch (\Throwable $e) {
            report($e);

            return false;
        }
    }
}
