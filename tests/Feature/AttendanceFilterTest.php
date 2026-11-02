<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AttendanceFilterTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Employee $first;

    private Employee $second;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::create(['name' => 'view-attendance']);

        $this->user = User::factory()->create();
        $this->user->givePermissionTo('view-attendance');

        $this->first = Employee::create(['name' => 'Alice One']);
        $this->second = Employee::create(['name' => 'Bob Two']);

        Attendance::create(['employee_id' => $this->first->id, 'date' => '2026-09-01', 'status' => 'present']);
        Attendance::create(['employee_id' => $this->second->id, 'date' => '2026-09-02', 'status' => 'absent']);
    }

    public function test_attendance_datatable_can_be_filtered_by_employee(): void
    {
        $response = $this->actingAs($this->user)->getJson(
            route('attendance.datatable', ['employee_id' => $this->first->id])
        );

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('Alice One', $response->json('data.0.employee_id'));
    }

    public function test_attendance_datatable_can_be_filtered_by_status_and_date_range(): void
    {
        $response = $this->actingAs($this->user)->getJson(route('attendance.datatable', [
            'status' => 'absent',
            'from' => '2026-09-02',
            'to' => '2026-09-02',
        ]));

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('Bob Two', $response->json('data.0.employee_id'));
    }

    public function test_attendance_datatable_without_filters_returns_everything(): void
    {
        $response = $this->actingAs($this->user)->getJson(route('attendance.datatable'));

        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
    }
}
