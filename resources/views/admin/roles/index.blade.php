@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<div class="module-index">

    {{-- MAIN TABLE CARD --}}
    <div class="card">
        <div class="card-header border-0 d-flex justify-content-between align-items-center pt-4 pb-2" style="border-bottom: 1px solid #f0f0f0 !important;">
            <h5 class="font-weight-bold text-dark mb-0" style="font-size:1.1rem;">
                <i class="fas fa-user-shield mr-2 text-primary"></i>All Roles
            </h5>
            <div class="d-flex align-items-center gap-2">
                @can('create-roles')
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> New Role
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="width:100%">
                    <thead>
                        <tr style="background:#f8f9fa; border-top: 1px solid #e9ecef; border-bottom: 2px solid #dee2e6;">
                            <th style="width:60px; font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">#</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Name</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Users</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Permissions</th>
                            <th style="width:140px; font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Actions</th>
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
    </div>
</div>
@endsection

@push('styles')
<style>
    .module-index .card { border: none; box-shadow: 0 0 15px rgba(0,0,0,.04); border-radius: 12px; }
    .module-index thead th { white-space: nowrap; }
    .module-index tbody tr { transition: background .15s; }
    .module-index tbody tr:hover { background: #f0f4ff !important; }
    .module-index td { vertical-align: middle; }
</style>
@endpush
