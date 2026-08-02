<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('view-users');
        $roles = Role::orderBy('name')->pluck('name', 'id');

        return view('admin.users.index', compact('roles'));
    }

    public function datatable()
    {
        $this->authorize('view-users');

        return DataTables::eloquent(User::query())
            ->addIndexColumn()
            ->addColumn('roles', function (User $user) {
                return $user->getRoleNames()->implode(', ');
            })
            ->editColumn('created_at', fn (User $user) => $user->created_at?->format('d M Y H:i'))
            ->editColumn('updated_at', fn ($m) => $m->updated_at?->format('d M Y H:i'))
            ->addColumn('actions', function (User $user) {
                return view('admin.users.partials.actions', ['row' => $user, 'route' => 'users'])->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        $this->authorize('create-users');
        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-users');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:7', 'confirmed'],
            'roles' => ['array'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->syncRoles(Role::whereIn('id', $data['roles'] ?? [])->get());

        activity()->causedBy(auth()->user())->performedOn($user)->withProperties(['roles' => $data['roles'] ?? []])->event('created')->log('created');

        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        $this->authorize('update-users');
        $roles = Role::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update-users');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:7', 'confirmed'],
            'roles' => ['array'],
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (! empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        $roleIds = $data['roles'] ?? [];
        $roles = Role::whereIn('id', $roleIds)->get();

        $user->syncRoles($roles);

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete-users');

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}



