@extends('layouts.app')

@section('title', 'Attendance')

@section('content')
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $today }}</h3>
                <p>Records Today</p>
            </div>
            <div class="icon"><i class="fas fa-clipboard-check"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $present }}</h3>
                <p>Present Today</p>
            </div>
            <div class="icon"><i class="fas fa-user-check"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $leave }}</h3>
                <p>On Leave Today</p>
            </div>
            <div class="icon"><i class="fas fa-umbrella-beach"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-calendar-check mr-1"></i> Attendance</h3>
                <div class="card-tools">
                    @can('create-attendance')
                        <a href="{{ route('attendance.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Mark Attendance</a>
                    @endcan
                </div>
            </div>
            <div class="card-body p-0">
                <table id="dt-attendance" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Employee</th>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                            <th>Notes</th>
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
    $('#dt-attendance').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("attendance.datatable") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'employee_id', name: 'employee_id' },
            { data: 'date', name: 'date' },
            { data: 'check_in', name: 'check_in' },
            { data: 'check_out', name: 'check_out' },
            { data: 'status', name: 'status' },
            { data: 'notes', name: 'notes' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[2, 'desc']]
    });
});
</script>
@endpush