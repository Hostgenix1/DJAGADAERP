<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

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

        $salesPerms = ['view-dashboard', 'view-customers', 'create-customers', 'update-customers', 'view-contacts', 'create-contacts', 'update-contacts',
            'view-leads', 'create-leads', 'update-leads', 'view-follow-ups', 'create-follow-ups', 'update-follow-ups',
            'view-communications', 'create-communications', 'update-communications',
            'view-brands', 'view-product_categories', 'view-suppliers', 'view-products',
            'view-quotes', 'create-quotes', 'update-quotes',
            'view-invoices', 'create-invoices', 'update-invoices',
            'view-purchase-orders', 'view-supplier-bills',
            'view-payments', 'create-payments',
            'view-documents', 'create-documents'];
        $sales = Role::updateOrCreate(['name' => 'Sales'], ['guard_name' => 'web']);
        $sales->syncPermissions(array_filter($all, fn ($p) => in_array($p, $salesPerms)));

        // Read-only executive view.
        $ceo = Role::updateOrCreate(['name' => 'CEO'], ['guard_name' => 'web']);
        $ceo->syncPermissions(array_values(array_filter($all, fn ($p) => str_starts_with($p, 'view-'))));

        $financePerms = [
            'view-dashboard', 'view-customers', 'view-suppliers', 'view-products',
            'view-quotes', 'view-invoices', 'create-invoices', 'update-invoices', 'delete-invoices',
            'view-payments', 'create-payments', 'update-payments', 'delete-payments',
            'view-expenses', 'create-expenses', 'update-expenses', 'delete-expenses',
            'view-purchase-orders', 'view-purchase-requests', 'view-supplier-bills',
            'create-supplier-bills', 'update-supplier-bills', 'delete-supplier-bills',
            'view-currencies', 'create-currencies', 'update-currencies', 'delete-currencies',
            'view-bank-accounts', 'create-bank-accounts', 'update-bank-accounts', 'delete-bank-accounts',
            'view-selling-prices', 'view-supplier-prices', 'view-pricing-costs',
            'view-reports', 'view-documents', 'create-documents',
        ];
        $finance = Role::updateOrCreate(['name' => 'Finance'], ['guard_name' => 'web']);
        $finance->syncPermissions(array_values(array_filter($all, fn ($p) => in_array($p, $financePerms, true))));

        $hrPerms = [
            'view-dashboard',
            'view-employees', 'create-employees', 'update-employees', 'delete-employees',
            'view-attendance', 'create-attendance', 'update-attendance', 'delete-attendance',
            'view-leaves', 'create-leaves', 'update-leaves', 'delete-leaves',
            'view-payroll', 'create-payroll', 'update-payroll', 'delete-payroll',
            'view-own-payslips', 'view-documents', 'create-documents', 'view-reports',
        ];
        $hr = Role::updateOrCreate(['name' => 'HR'], ['guard_name' => 'web']);
        $hr->syncPermissions(array_values(array_filter($all, fn ($p) => in_array($p, $hrPerms, true))));

        $warehousePerms = [
            'view-dashboard', 'view-products', 'view-product_categories', 'view-brands', 'view-suppliers',
            'view-stock', 'create-stock-movements', 'delete-stock-movements',
            'view-warehouses', 'create-warehouses', 'update-warehouses', 'delete-warehouses',
            'view-purchase-orders', 'view-purchase-requests', 'create-purchase-requests', 'update-purchase-requests',
            'view-documents', 'create-documents', 'view-reports',
        ];
        $warehouse = Role::updateOrCreate(['name' => 'Warehouse'], ['guard_name' => 'web']);
        $warehouse->syncPermissions(array_values(array_filter($all, fn ($p) => in_array($p, $warehousePerms, true))));

        $logisticsPerms = [
            'view-dashboard', 'view-shipments', 'create-shipments', 'update-shipments', 'delete-shipments',
            'view-orders', 'update-orders', 'view-customers', 'view-contacts', 'view-invoices',
            'view-documents', 'create-documents', 'view-reports',
        ];
        $logistics = Role::updateOrCreate(['name' => 'Logistics'], ['guard_name' => 'web']);
        $logistics->syncPermissions(array_values(array_filter($all, fn ($p) => in_array($p, $logisticsPerms, true))));

        // Read-only access without administrative, payroll or settings data.
        $viewerExcluded = ['view-users', 'view-roles', 'view-settings', 'view-payroll', 'view-own-payslips'];
        $viewer = Role::updateOrCreate(['name' => 'Viewer'], ['guard_name' => 'web']);
        $viewer->syncPermissions(array_values(array_filter(
            $all,
            fn ($p) => str_starts_with($p, 'view-') && ! in_array($p, $viewerExcluded, true)
        )));

        $user = User::where('email', 'admin@djagada.com')->first();
        if ($user) {
            $user->syncRoles(['Super Admin']);
        }
    }
}
