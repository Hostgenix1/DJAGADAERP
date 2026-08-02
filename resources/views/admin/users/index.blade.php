@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="module-index">

    {{-- MAIN TABLE CARD --}}
    <div class="card">
        <div class="card-header border-0 d-flex justify-content-between align-items-center pt-4 pb-2" style="border-bottom: 1px solid #f0f0f0 !important;">
            <h5 class="font-weight-bold text-dark mb-0" style="font-size:1.1rem;">
                <i class="fas fa-user-cog mr-2 text-primary"></i>All Users
            </h5>
            <div class="d-flex align-items-center gap-2">
                @can('create-users')
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> New User
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="dt-users" class="table table-hover" style="width:100%">
                    <thead>
                        <tr style="background:#f8f9fa; border-top: 1px solid #e9ecef; border-bottom: 2px solid #dee2e6;">
                            <th style="width:60px; font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">#</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Name</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Email</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Roles</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Created</th>
                            <th style="width:140px; font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
<style>
    .module-index .card { border: none; box-shadow: 0 0 15px rgba(0,0,0,.04); border-radius: 12px; }
    #dt-users thead th { white-space: nowrap; }
    #dt-users tbody tr { transition: background .15s; }
    #dt-users tbody tr:hover { background: #f0f4ff !important; }
    #dt-users td { vertical-align: middle; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    $('#dt-users').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.users.data") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'roles', name: 'roles', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
        language: { search: '', searchPlaceholder: 'Search...' }
    });
});
</script>
@endpush
