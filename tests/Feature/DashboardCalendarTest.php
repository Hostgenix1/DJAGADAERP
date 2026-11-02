<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\FollowUp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class DashboardCalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_calendar_returns_follow_up_events(): void
    {
        $user = User::factory()->create();
        Permission::create(['name' => 'view-dashboard']);
        $user->givePermissionTo('view-dashboard');

        $customer = Customer::create(['company_name' => 'Calendar Co']);

        FollowUp::create([
            'followable_type' => Customer::class,
            'followable_id' => $customer->id,
            'type' => 'call',
            'due_date' => now()->startOfMonth()->addDays(3)->toDateString(),
            'note' => 'Call the client',
        ]);

        $response = $this->actingAs($user)->getJson(route('dashboard.calendar', [
            'start' => now()->startOfMonth()->toDateString(),
            'end' => now()->endOfMonth()->toDateString(),
        ]));

        $response->assertOk();
        $response->assertJsonFragment(['title' => 'Follow-up: Call']);
    }
}
