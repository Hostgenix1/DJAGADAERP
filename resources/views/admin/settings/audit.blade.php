@extends('layouts.app')

@section('title', 'Audit Log')

@section('content')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Audit Log</h3>
    </div>
    <div class="card-body">
        <table id="dt-audit" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th style="width: 40px">#</th>
                <th>User</th>
                <th>Event</th>
                <th>Module</th>
                <th>Description</th>
                <th>Date</th>
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
        $('#dt-audit').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.settings.audit.data') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'user', name: 'user' },
                { data: 'event', name: 'event' },
                { data: 'subject', name: 'subject' },
                { data: 'description', name: 'description' },
                { data: 'created_at', name: 'created_at' }
            ],
            order: [[5, 'desc']]
        });
    });
</script>
@endpush