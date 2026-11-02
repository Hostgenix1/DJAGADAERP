<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class LeaveFilterTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Employee $first;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::create(['name' => 'view-leaves']);

        $this->user = User::factory()->create();
        $this->user->givePermissionTo('view-leaves');

        $this->first = Employee::create(['name' => 'Alice One']);
        $second = Employee::create(['name' => 'Bob Two']);

        Leave::create([
            'employee_id' => $this->first->id,
            'type' => 'annual',
            'start_date' => '2026-09-01',
            'end_date' => '2026-09-03',
            'days' => 3,
            'status' => 'approved',
        ]);

        Leave::create([
            'employee_id' => $second->id,
            'type' => 'sick',
            'start_date' => '2026-10-10',
            'end_date' => '2026-10-12',
            'days' => 3,
            'status' => 'pending',
        ]);
    }

    public function test_leaves_datatable_can_be_filtered_by_employee(): void
    {
        $response = $this->actingAs($this->user)->getJson(
            route('leaves.datatable', ['employee_id' => $this->first->id])
        );

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('Alice One', $response->json('data.0.employee_id'));
    }

    public function test_leaves_datatable_can_be_filtered_by_type_and_status(): void
    {
        $response = $this->actingAs($this->user)->getJson(
            route('leaves.datatable', ['type' => 'sick', 'status' => 'pending'])
        );

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('Bob Two', $response->json('data.0.employee_id'));
    }

    public function test_leaves_datatable_date_range_uses_overlap_matching(): void
    {
        // 2 Sep falls inside Alice's leave window, even though it is not a start/end date.
        $response = $this->actingAs($this->user)->getJson(
            route('leaves.datatable', ['from' => '2026-09-02', 'to' => '2026-09-02'])
        );

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('Alice One', $response->json('data.0.employee_id'));
    }

    public function test_leaves_datatable_without_filters_returns_everything(): void
    {
        $response = $this->actingAs($this->user)->getJson(route('leaves.datatable'));

        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
    }
}
