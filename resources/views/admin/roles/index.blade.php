@extends('layouts.app')

@section('title', 'Roles')

{{--
  Role Management List - Settings Module
  Module: Settings
  Features: Role list with user count, permission badges, permission-based create/edit/delete actions, grouped permission display, delete confirmation
  Version: 1.0.0
--}}

@section('content')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Roles</h3>
        <div class="card-tools">
            @can('create-roles')
                <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> New</a>
            @endcan
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover mb-0">
            <thead>
            <tr>
                <th style="width: 40px">#</th>
                <th>Name</th>
                <th>Users</th>
                <th>Permissions</th>
                <th style="width: 140px">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($roles as $i => $role)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><span class="badge badge-dark text-capitalize">{{ $role->name }}</span></td>
                    <td>{{ $role->users_count }}</td>
                    <td>
                        @foreach($role->permissions->take(5) as $perm)
                            <span class="badge badge-light border">{{ $perm->name }}</span>
                        @endforeach
                        @if($role->permissions->count() > 5)
                            <span class="badge badge-light border">+{{ $role->permissions->count() - 5 }} more</span>
                        @endif
                    </td>
                    <td>
                        @can('update-roles')
                            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-info" title="Edit"><i class="fas fa-edit"></i></a>
                        @endcan
                        @can('delete-roles')
                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this role?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection