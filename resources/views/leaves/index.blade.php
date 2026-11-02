@extends('layouts.app')

@section('title', 'Leave Management')

@section('content')
<div class="row">
    <div class="col-lg-6 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $pending }}</h3>
                <p>Pending Requests</p>
            </div>
            <div class="icon"><i class="fas fa-hourglass-half"></i></div>
        </div>
    </div>
    <div class="col-lg-6 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $approved }}</h3>
                <p>Approved Leaves</p>
            </div>
            <div class="icon"><i class="fas fa-umbrella-beach"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-umbrella-beach mr-1"></i> Leave Requests</h3>
                <div class="card-tools">
                    @can('create-leaves')
                        <a href="{{ route('leaves.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Leave</a>
                    @endcan
                </div>
            </div>
            <div class="card-body border-bottom py-2">
                <div class="form-row align-items-end">
                    <div class="col-md-3 mb-1 mb-md-0">
                        <label class="mb-0 small text-muted">Employee</label>
                        <select id="filter-employee" class="form-control form-control-sm">
                            <option value="">All employees</option>
                            @foreach($employees as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-1 mb-md-0">
                        <label class="mb-0 small text-muted">Type</label>
                        <select id="filter-type" class="form-control form-control-sm">
                            <option value="">All types</option>
                            @foreach(['annual' => 'Annual', 'sick' => 'Sick', 'unpaid' => 'Unpaid', 'other' => 'Other'] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-1 mb-md-0">
                        <label class="mb-0 small text-muted">Status</label>
                        <select id="filter-status" class="form-control form-control-sm">
                            <option value="">All statuses</option>
                            @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-1 mb-md-0">
                        <label class="mb-0 small text-muted">From</label>
                        <input type="date" id="filter-from" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2 mb-1 mb-md-0">
                        <label class="mb-0 small text-muted">To</label>
                        <input type="date" id="filter-to" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-1 mb-1 mb-md-0 text-right">
                        <button type="button" id="filter-apply" class="btn btn-sm btn-primary" title="Apply"><i class="fas fa-filter"></i></button>
                        <button type="button" id="filter-reset" class="btn btn-sm btn-default" title="Reset"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <table id="dt-leaves" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Employee</th>
                            <th>Type</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th>Reason</th>
                            <th style="width:120px">Actions</th>
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
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    var table = $('#dt-leaves').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("leaves.datatable") }}',
            data: function (d) {
                d.employee_id = $('#filter-employee').val();
                d.type = $('#filter-type').val();
                d.status = $('#filter-status').val();
                d.from = $('#filter-from').val();
                d.to = $('#filter-to').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'employee_id', name: 'employee_id' },
            { data: 'type', name: 'type' },
            { data: 'start_date', name: 'start_date' },
            { data: 'end_date', name: 'end_date' },
            { data: 'days', name: 'days' },
            { data: 'status', name: 'status' },
            { data: 'reason', name: 'reason' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[3, 'desc']]
    });

    function reloadTable() {
        table.ajax.reload();
    }

    $('#filter-employee, #filter-type, #filter-status, #filter-from, #filter-to').on('change', reloadTable);
    $('#filter-apply').on('click', reloadTable);
    $('#filter-reset').on('click', function () {
        $('#filter-employee, #filter-type, #filter-status, #filter-from, #filter-to').val('');
        reloadTable();
    });
});
</script>
@endpush