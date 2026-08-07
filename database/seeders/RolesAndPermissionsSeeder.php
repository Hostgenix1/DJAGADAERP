<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = config('permissions', []);
        $all = [];

        foreach ($permissions as $group => $list) {
            foreach ($list as $permission) {
                Permission::updateOrCreate(['name' => $permission], ['guard_name' => 'web']);
                $all[] = $permission;
            }
        }

        $superAdmin = Role::updateOrCreate(['name' => 'Super Admin'], ['guard_name' => 'web']);
        $superAdmin->syncPermissions($all);

        $staff = Role::updateOrCreate(['name' => 'Staff'], ['guard_name' => 'web']);
        $staffViews = array_values(array_filter($all, fn ($p) => str_starts_with($p, 'view-')));
        $staff->syncPermissions($staffViews);

        $salesPerms = ['view-dashboard','view-customers','create-customers','update-customers','view-contacts','create-contacts','update-contacts',
            'view-leads','create-leads','update-leads','view-follow-ups','create-follow-ups','update-follow-ups',
            'view-communications','create-communications','update-communications',
            'view-brands','view-product_categories','view-suppliers','view-products',
            'view-quotes','create-quotes','update-quotes',
            'view-invoices','create-invoices','update-invoices',
            'view-payments','create-payments',
            'view-documents','create-documents'];
        $sales = Role::updateOrCreate(['name' => 'Sales'], ['guard_name' => 'web']);
        $sales->syncPermissions(array_filter($all, fn ($p) => in_array($p, $salesPerms)));

        $user = User::where('email', 'admin@djagada.com')->first();
        if ($user) {
            $user->syncRoles(['Super Admin']);
        }
    }
}