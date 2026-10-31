<?php

namespace Tests\Unit;

use App\Services\PayrollService;
use PHPUnit\Framework\TestCase;

class PayrollCalculationTest extends TestCase
{
    public function test_absence_and_allowances_are_applied_to_net_salary(): void
    {
        $calc = PayrollService::calculate(
            baseSalary: 6000,
            workingDays: 30,
            absentDays: 2,
            transportAllowance: 500,
            bonus: 200,
            otherAllowances: 0,
            otherDeductions: 100,
        );

        $this->assertSame(200.0, $calc['perDay']);
        $this->assertSame(400.0, $calc['absenceDeduction']);
        $this->assertSame(700.0, $calc['allowances']);
        $this->assertSame(500.0, $calc['deductions']);
        $this->assertSame(6200.0, $calc['net']);
    }

    public function test_absence_deduction_override_is_used_when_provided(): void
    {
        $calc = PayrollService::calculate(
            baseSalary: 3000,
            workingDays: 30,
            absentDays: 5,
            transportAllowance: 0,
            bonus: 0,
            otherAllowances: 0,
            otherDeductions: 0,
            absenceDeductionOverride: 250,
        );

        $this->assertSame(250.0, $calc['absenceDeduction']);
        $this->assertSame(2750.0, $calc['net']);
    }

    public function test_overtime_is_added_to_allowances(): void
    {
        $calc = PayrollService::calculate(
            baseSalary: 3000,
            workingDays: 30,
            absentDays: 0,
            transportAllowance: 0,
            bonus: 0,
            otherAllowances: 0,
            otherDeductions: 0,
            overtimeAmount: 150,
        );

        $this->assertSame(150.0, $calc['allowances']);
        $this->assertSame(3150.0, $calc['net']);
    }
}
