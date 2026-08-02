@extends('layouts.app')

@section('title', 'Users')

{{--
  User Management List - Settings Module
  Module: Settings
  Features: Server-side DataTable, user name/email/roles/created columns, permission-based create button, search, pagination, inline edit/delete actions
  Version: 1.0.0
--}}

@section('content')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Users</h3>
        <div class="card-tools">
            @can('create-users')
                <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> New</a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <table id="dt-users" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th style="width: 40px">#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Created</th>
                <th style="width: 120px">Actions</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(function () {
        $('#dt-users').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.users.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'roles', name: 'roles', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
            order: [[1, 'asc']]
        });
    });
</script>
@endpush