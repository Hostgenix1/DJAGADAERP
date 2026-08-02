@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-shield mr-1"></i> Roles</h3>
                <div class="card-tools">
                    @can('create-roles')
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> New Role
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Name</th>
                            <th>Users</th>
                            <th>Permissions</th>
                            <th style="width:120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $i => $role)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->users_count }}</td>
                                <td>
                                    @foreach($role->permissions->take(5) as $perm)
                                        <span class="badge badge-info">{{ $perm->name }}</span>
                                    @endforeach
                                    @if($role->permissions->count() > 5)
                                        <span class="badge badge-secondary">+{{ $role->permissions->count() - 5 }} more</span>
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
    </div>
</div>
@endsection
