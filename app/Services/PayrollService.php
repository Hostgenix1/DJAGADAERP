<?php

namespace App\Services;

use App\Mail\PayslipMailable;
use App\Models\Attendance;
use App\Models\CompanyBankAccount;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Leave;
use App\Models\PayrollEntry;
use App\Support\Masking;
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
        float $overtimeAmount = 0,
    ): array {
        $workingDays = $workingDays > 0 ? $workingDays : self::DAYS_DIVISOR_DEFAULT;
        $perDay = round($baseSalary / $workingDays, 2);
        $absenceDeduction = $absenceDeductionOverride ?? round($perDay * $absentDays, 2);

        $allowances = round($transportAllowance + $bonus + $otherAllowances + $overtimeAmount, 2);
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
            'overtime_hours' => 0,
            'overtime_amount' => 0,
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
    public static function postSalaryExpense(PayrollEntry $payrollEntry): ?Expense
    {
        $marker = 'PAYROLL:'.$payrollEntry->id;

        $existing = Expense::where('category', 'payroll')
            ->where('notes', 'like', '%'.$marker.'%')
            ->first();
        if ($existing) {
            return $existing;
        }

        if ((float) $payrollEntry->net_salary == 0.0) {
            return null;
        }

        return Expense::create([
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
     * Build the data contract for the payslip view: earnings and deductions are
     * separate lists (deduction amounts positive) and the totals come straight
     * from the payroll entry — the template never recalculates them.
     */
    public static function payslipViewData(PayrollEntry $payrollEntry): array
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
            $binary = file_get_contents($fullPath);
            if (in_array($ext, ['png', 'gif', 'webp'], true)) {
                $binary = self::cropLogoPadding($binary) ?? $binary;
            }
            $logoBase64 = 'data:'.$mime.';base64,'.base64_encode($binary);
        }

        $company = [
            'name' => $svc->get('company_name'),
            'address' => $svc->get('company_address'),
            'city' => $svc->get('company_city'),
            'country' => $svc->get('company_country'),
            'email' => $svc->get('company_email'),
            'phone' => $svc->get('company_phone'),
            'trn' => $svc->get('company_trn'),
            'license' => $svc->get('company_trade_license'),
            'routing_code' => $svc->get('company_routing_code'),
            'establishment_id' => $svc->get('company_establishment_id'),
            'wps_sif_reference' => $svc->get('company_wps_sif_reference'),
            'show_logo' => $svc->get('show_logo_on_docs'),
            'logo_url' => $logoBase64,
        ];

        // Payment block: employee bank first, company default bank as fallback.
        // Everything masked; the whole block hides in the template when empty.
        $employee = $payrollEntry->employee;
        $companyBank = CompanyBankAccount::where('is_default', true)->where('is_active', true)->first()
            ?? CompanyBankAccount::where('is_active', true)->first();

        $payment = [
            'bank_name' => $employee?->bank_name ?: $companyBank?->bank_name,
            'iban_masked' => Masking::iban($employee?->iban ?: $companyBank?->iban),
            'routing_code' => $company['routing_code'],
            'wps_sif_reference' => $company['wps_sif_reference'],
            'establishment_id' => $company['establishment_id'],
        ];

        $maskedEmiratesId = Masking::emiratesId($employee?->emirates_id);

        $currencyCode = $payrollEntry->currency?->code ?? $employee?->currency?->code;

        $overtimeHours = (float) $payrollEntry->overtime_hours;
        $overtimeHoursLabel = $overtimeHours <= 0
            ? '0'
            : (fmod($overtimeHours, 1.0) === 0.0 ? number_format($overtimeHours, 1, '.', '') : self::trimNumber($overtimeHours));

        $earnings = [
            ['label' => 'Basic salary', 'amount' => (float) $payrollEntry->gross_salary, 'note' => null],
        ];
        if ((float) $payrollEntry->transport_allowance > 0) {
            $earnings[] = ['label' => 'Transport allowance', 'amount' => (float) $payrollEntry->transport_allowance, 'note' => null];
        }
        if ((float) $payrollEntry->bonus > 0) {
            $earnings[] = ['label' => 'Bonus', 'amount' => (float) $payrollEntry->bonus, 'note' => null];
        }
        if ($overtimeHours > 0 && (float) $payrollEntry->overtime_amount > 0) {
            $rate = number_format((float) $payrollEntry->overtime_amount / $overtimeHours, 2);
            $earnings[] = ['label' => 'Overtime', 'amount' => (float) $payrollEntry->overtime_amount, 'note' => $overtimeHoursLabel.' hrs — '.$rate];
        }
        if ((float) $payrollEntry->other_allowances > 0) {
            $earnings[] = ['label' => 'Other allowance', 'amount' => (float) $payrollEntry->other_allowances, 'note' => null];
        }

        $deductions = [];
        if ((float) $payrollEntry->absence_deduction > 0) {
            $deductions[] = [
                'label' => 'Unpaid leave',
                'amount' => (float) $payrollEntry->absence_deduction,
                'note' => self::trimNumber((float) $payrollEntry->absent_days).' '.((float) $payrollEntry->absent_days == 1.0 ? 'day' : 'days'),
            ];
        }
        if ((float) $payrollEntry->other_deductions > 0) {
            $deductions[] = ['label' => 'Other deductions', 'amount' => (float) $payrollEntry->other_deductions, 'note' => null];
        }

        $totals = [
            'gross' => (float) $payrollEntry->gross_salary + (float) $payrollEntry->allowances,
            'deductions' => (float) $payrollEntry->deductions,
            'net' => (float) $payrollEntry->net_salary,
        ];

        $inWords = Masking::amountInWords((float) $payrollEntry->net_salary, $currencyCode);

        return compact(
            'payrollEntry', 'company', 'payment', 'maskedEmiratesId', 'inWords',
            'earnings', 'deductions', 'totals', 'currencyCode', 'overtimeHoursLabel'
        );
    }

    /**
     * Render the payslip PDF exactly as designed (A4, one page).
     */
    public static function renderPayslip(PayrollEntry $payrollEntry)
    {
        return Pdf::loadView('payroll.payslip', self::payslipViewData($payrollEntry))->setPaper('a4');
    }

    /**
     * Store the final payslip PDF once payroll is paid (private disk).
     */
    public static function storePayslip(PayrollEntry $payrollEntry): ?string
    {
        try {
            $path = self::payslipStoragePath($payrollEntry);
            Storage::disk('local')->put($path, self::renderPayslip($payrollEntry)->output());
            $payrollEntry->forceFill(['payslip_path' => $path])->save();

            return $path;
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }

    /**
     * Stored PDF when available, otherwise rendered on demand.
     */
    public static function payslipPdf(PayrollEntry $payrollEntry): string
    {
        if ($payrollEntry->payslip_path && Storage::disk('local')->exists($payrollEntry->payslip_path)) {
            return Storage::disk('local')->get($payrollEntry->payslip_path);
        }

        return self::renderPayslip($payrollEntry)->output();
    }

    public static function payslipStoragePath(PayrollEntry $payrollEntry): string
    {
        return 'payslips/'.$payrollEntry->employee_id.'/payslip-'.$payrollEntry->period.'.pdf';
    }

    /**
     * Format "5.00" as "5" and "5.50" as "5.5".
     */
    protected static function trimNumber(float $value, int $decimals = 2): string
    {
        return rtrim(rtrim(number_format($value, $decimals, '.', ''), '0'), '.');
    }

    /**
     * Trim transparent padding around an uploaded logo so it prints at a
     * sensible size in the payslip header (GD only, fails softly).
     */
    protected static function cropLogoPadding(string $binary): ?string
    {
        if (! function_exists('imagecreatefromstring')) {
            return null;
        }

        $image = @imagecreatefromstring($binary);
        if (! $image) {
            return null;
        }

        if (! imageistruecolor($image)) {
            imagepalettetotruecolor($image);
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $minX = $width;
        $minY = $height;
        $maxX = -1;
        $maxY = -1;

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $rgba = imagecolorat($image, $x, $y);
                $alpha = ($rgba >> 24) & 0x7F;

                if ($alpha < 120) {
                    $minX = min($minX, $x);
                    $minY = min($minY, $y);
                    $maxX = max($maxX, $x);
                    $maxY = max($maxY, $y);
                }
            }
        }

        if ($maxX < 0) {
            imagedestroy($image);

            return null;
        }

        // No transparency to trim (e.g. flat JPEG-like image).
        if ($minX === 0 && $minY === 0 && $maxX === $width - 1 && $maxY === $height - 1) {
            imagedestroy($image);

            return null;
        }

        $pad = 4;
        $minX = max(0, $minX - $pad);
        $minY = max(0, $minY - $pad);
        $maxX = min($width - 1, $maxX + $pad);
        $maxY = min($height - 1, $maxY + $pad);

        $cropWidth = $maxX - $minX + 1;
        $cropHeight = $maxY - $minY + 1;

        $cropped = imagecreatetruecolor($cropWidth, $cropHeight);
        imagealphablending($cropped, false);
        imagesavealpha($cropped, true);
        imagecopy($cropped, $image, 0, 0, $minX, $minY, $cropWidth, $cropHeight);

        ob_start();
        imagepng($cropped);
        $output = ob_get_clean();

        imagedestroy($image);
        imagedestroy($cropped);

        return $output ?: null;
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
