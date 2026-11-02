<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\PayrollEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PayrollFilterTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Employee $first;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::create(['name' => 'view-payroll']);

        $this->user = User::factory()->create();
        $this->user->givePermissionTo('view-payroll');

        $this->first = Employee::create(['name' => 'Alice One']);
        $second = Employee::create(['name' => 'Bob Two']);

        PayrollEntry::create([
            'employee_id' => $this->first->id,
            'period' => '2026-09',
            'net_salary' => 5000,
            'status' => PayrollEntry::STATUS_DRAFT,
        ]);

        PayrollEntry::create([
            'employee_id' => $second->id,
            'period' => '2026-10',
            'net_salary' => 6000,
            'status' => PayrollEntry::STATUS_PAID,
        ]);
    }

    public function test_payroll_datatable_can_be_filtered_by_employee(): void
    {
        $response = $this->actingAs($this->user)->getJson(
            route('payroll.datatable', ['employee_id' => $this->first->id])
        );

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('Alice One', $response->json('data.0.employee_id'));
    }

    public function test_payroll_datatable_can_be_filtered_by_period_and_status(): void
    {
        $response = $this->actingAs($this->user)->getJson(
            route('payroll.datatable', ['period' => '2026-10', 'status' => 'paid'])
        );

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('Bob Two', $response->json('data.0.employee_id'));
    }

    public function test_payroll_datatable_without_filters_returns_everything(): void
    {
        $response = $this->actingAs($this->user)->getJson(route('payroll.datatable'));

        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
    }
}
