<?php

namespace Tests\Feature;

use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolesAndPermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_agreed_role_model_is_seeded(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $expected = ['Super Admin', 'CEO', 'Sales', 'Finance', 'HR', 'Warehouse', 'Logistics', 'Viewer'];

        foreach ($expected as $role) {
            $this->assertTrue(Role::where('name', $role)->exists(), "Missing role: {$role}");
        }
    }

    public function test_roles_receive_the_expected_permissions(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->assertTrue(Role::findByName('Finance')->hasPermissionTo('create-invoices'));
        $this->assertTrue(Role::findByName('HR')->hasPermissionTo('view-payroll'));
        $this->assertTrue(Role::findByName('Warehouse')->hasPermissionTo('view-stock'));
        $this->assertTrue(Role::findByName('Warehouse')->hasPermissionTo('create-purchase-requests'));
        $this->assertTrue(Role::findByName('Logistics')->hasPermissionTo('view-shipments'));
        $this->assertTrue(Role::findByName('Viewer')->hasPermissionTo('view-customers'));
        $this->assertFalse(Role::findByName('Viewer')->hasPermissionTo('view-users'));
        $this->assertFalse(Role::findByName('Viewer')->hasPermissionTo('view-payroll'));
    }
}
