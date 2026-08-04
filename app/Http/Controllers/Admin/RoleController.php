<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $this->authorize('view-roles');
        $roles = Role::withCount('users')->orderBy('name')->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $this->authorize('create-roles');
        $permissions = $this->groupedPermissions();

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-roles');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name'],
            'permissions' => ['array'],
        ]);

        $role = Role::create(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        activity()->causedBy(auth()->user())->performedOn($role)->withProperties(['permissions' => $data['permissions'] ?? []])->event('created')->log('created');

        return redirect()->route('admin.roles.index')->with('success', 'Role created.');
    }

    public function edit(Role $role)
    {
        $this->authorize('update-roles');
        $permissions = $this->groupedPermissions();
        $has = $role->permissions->pluck('name')->all();

        return view('admin.roles.edit', compact('role', 'permissions', 'has'));
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('update-roles');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name,'.$role->id],
            'permissions' => ['array'],
        ]);

        $role->update(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated.');
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete-roles');

        if ($role->name === 'Super Admin') {
            return back()->with('error', 'The Super Admin role cannot be deleted.');
        }

        if ($role->users()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete role with assigned users. Remove users from this role first.']);
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted.');
    }

    protected function groupedPermissions(): array
    {
        $perms = Permission::orderBy('name')->get();
        $groups = [];

        foreach ($perms as $perm) {
            [$action, $resource] = array_pad(explode('-', $perm->name, 2), 2, 'other');
            $groups[$resource][$perm->name] = ucfirst($action);
        }

        ksort($groups);

        return $groups;
    }
}



