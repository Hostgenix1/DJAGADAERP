<?php

namespace Tests\Feature;

use App\Mail\PayslipMailable;
use App\Models\Currency;
use App\Models\Employee;
use App\Models\PayrollEntry;
use App\Models\User;
use App\Services\PayrollService;
use App\Support\Masking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PayslipTest extends TestCase
{
    use RefreshDatabase;

    private function grant(array $permissions): User
    {
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $user = User::factory()->create();
        $user->givePermissionTo($permissions);

        return $user;
    }

    private function makeEmployee(array $overrides = []): Employee
    {
        return Employee::create(array_merge([
            'name' => 'Rania Haddad',
            'email' => 'rania@example.com',
            'date_of_birth' => '1990-04-12',
            'nationality' => 'Jordan',
            'emirates_id' => '784-1990-1234567-1',
            'labour_card_no' => '12345678901234',
            'bank_name' => 'Emirates NBD',
            'iban' => 'AE970330000019101860955',
            'base_salary' => 8000,
        ], $overrides));
    }

    private function makeEntry(Employee $employee, array $overrides = []): PayrollEntry
    {
        return PayrollEntry::create(array_merge([
            'employee_id' => $employee->id,
            'period' => '2026-09',
            'base_salary' => 8000,
            'working_days' => 30,
            'present_days' => 29,
            'absent_days' => 1,
            'leave_days' => 0,
            'overtime_hours' => 5,
            'overtime_amount' => 250,
            'transport_allowance' => 1000,
            'bonus' => 0,
            'other_allowances' => 500,
            'absence_deduction' => 425,
            'other_deductions' => 500,
            'gross_salary' => 8000,
            'allowances' => 1750,
            'deductions' => 925,
            'net_salary' => 8825,
            'status' => PayrollEntry::STATUS_PAID,
            'paid_on' => now()->toDateString(),
            'pay_date' => now()->toDateString(),
        ], $overrides));
    }

    public function test_payslip_data_contract_uses_english_money_and_masked_ids(): void
    {
        $employee = $this->makeEmployee();
        $currency = Currency::create(['code' => 'AED', 'name' => 'UAE Dirham', 'symbol' => 'AED']);
        $entry = $this->makeEntry($employee, ['currency_id' => $currency->id]);

        $data = PayrollService::payslipViewData($entry);

        $this->assertSame(9750.0, $data['totals']['gross']);
        $this->assertSame(925.0, $data['totals']['deductions']);
        $this->assertSame(8825.0, $data['totals']['net']);

        $this->assertSame('Unpaid leave', $data['deductions'][0]['label']);
        $this->assertSame(425.0, $data['deductions'][0]['amount']);
        $this->assertGreaterThan(0, $data['deductions'][0]['amount']);

        $overtime = collect($data['earnings'])->firstWhere('label', 'Overtime');
        $this->assertNotNull($overtime);
        $this->assertSame('5.0 hrs — 50.00', $overtime['note']);

        $this->assertSame(Masking::iban($employee->iban), $data['payment']['iban_masked']);
        $this->assertSame('784-XXXX-XXXXXXX-1', $data['maskedEmiratesId']);
        $this->assertSame('Eight thousand eight hundred twenty-five dirhams only', $data['inWords']);
    }

    public function test_payslip_html_matches_the_template_and_hides_status(): void
    {
        $employee = $this->makeEmployee();
        $entry = $this->makeEntry($employee);

        $html = view('payroll.payslip', PayrollService::payslipViewData($entry))->render();

        $this->assertStringContainsString('9,750.00', $html);
        $this->assertStringContainsString('8,825.00', $html);
        $this->assertStringContainsString('5.0 hrs', $html);
        $this->assertStringNotContainsString('-425.00', $html);
        $this->assertStringNotContainsString('Status:', $html);
        $this->assertStringContainsString('Payment details', $html);
    }

    public function test_payment_block_disappears_when_there_is_no_bank_information(): void
    {
        $employee = $this->makeEmployee(['bank_name' => null, 'iban' => null]);
        $entry = $this->makeEntry($employee);

        $html = view('payroll.payslip', PayrollService::payslipViewData($entry))->render();

        $this->assertStringNotContainsString('Payment details', $html);
    }

    public function test_marking_payroll_paid_stores_the_pdf_and_emails_it(): void
    {
        $user = $this->grant(['view-payroll', 'update-payroll']);

        Storage::fake('local');
        Mail::fake();

        $employee = $this->makeEmployee();
        $entry = $this->makeEntry($employee, [
            'status' => PayrollEntry::STATUS_DUE,
            'paid_on' => null,
        ]);

        $this->actingAs($user)->post(route('payroll.mark-paid', $entry))->assertRedirect();

        $entry->refresh();

        $this->assertSame(PayrollEntry::STATUS_PAID, $entry->status);
        $this->assertNotNull($entry->payslip_path);
        Storage::disk('local')->assertExists($entry->payslip_path);
        $this->assertStringStartsWith('%PDF', Storage::disk('local')->get($entry->payslip_path));
        Mail::assertSent(PayslipMailable::class);
    }

    public function test_employees_can_only_download_their_own_payslips(): void
    {
        $user = $this->grant(['view-own-payslips']);

        $employee = $this->makeEmployee();
        $entry = $this->makeEntry($employee, ['status' => PayrollEntry::STATUS_DUE]);

        // Not linked to the employee yet: forbidden.
        $this->actingAs($user)->get(route('my-payslips.download', $entry))->assertForbidden();

        $employee->update(['user_id' => $user->id]);
        $this->actingAs($user)->get(route('my-payslips.download', $entry))->assertOk();

        $otherEmployee = $this->makeEmployee(['name' => 'Other Person', 'email' => 'other@example.com']);
        $otherEntry = $this->makeEntry($otherEmployee, ['period' => '2026-08']);

        $this->actingAs($user)->get(route('my-payslips.download', $otherEntry))->assertForbidden();
    }

    public function test_payslip_pdf_stays_on_one_a4_page(): void
    {
        $employee = $this->makeEmployee();
        $entry = $this->makeEntry($employee);

        $pdf = PayrollService::renderPayslip($entry)->output();

        $pages = substr_count($pdf, '/Type /Page') - substr_count($pdf, '/Type /Pages');

        $this->assertSame(1, $pages);
    }
}
