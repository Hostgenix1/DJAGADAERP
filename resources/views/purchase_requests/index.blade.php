@extends('layouts.app')

@section('title', 'Purchase Requests')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h4 class="mb-0"><i class="fas fa-clipboard-check mr-1"></i> Purchase Requests</h4>
            </div>
            <div class="col-sm-6 text-right">
                @can('create-purchase-requests')
                    <a href="{{ route('purchase_requests.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> New Purchase Request</a>
                @endcan
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box"><span class="info-box-icon bg-secondary"><i class="fas fa-file-alt"></i></span>
                    <div class="info-box-content"><span class="info-box-text">Total Requests</span><span class="info-box-number">{{ $total }}</span></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box"><span class="info-box-icon bg-warning"><i class="fas fa-hourglass-half"></i></span>
                    <div class="info-box-content"><span class="info-box-text">Pending Approval</span><span class="info-box-number">{{ $pending }}</span></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box"><span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                    <div class="info-box-content"><span class="info-box-text">Approved</span><span class="info-box-number">{{ $approved }}</span></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box"><span class="info-box-icon bg-primary"><i class="fas fa-money-bill-wave"></i></span>
                    <div class="info-box-content"><span class="info-box-text">Estimated Value</span><span class="info-box-number">{{ $defaultCurrency?->symbol ?? '$' }} {{ number_format($estimated, 2) }}</span></div>
                </div>
            </div>
        </div>

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">All Purchase Requests</h3>
                <div class="card-tools">
                    <select id="filter-status" class="form-control form-control-sm" style="width:140px">
                        <option value="">All statuses</option>
                        @foreach(['draft','pending','approved','rejected','converted'] as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body">
                <table id="dt-purchase-requests" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Number</th>
                            <th>Requester</th>
                            <th>Department</th>
                            <th>Needed By</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th class="text-right">Estimated</th>
                            <th style="width:140px">Actions</th>
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
    const table = $('#dt-purchase-requests').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("purchase_requests.datatable") }}',
            data: function (d) { d.status = $('#filter-status').val(); }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'number', name: 'number' },
            { data: 'requester.name', name: 'requester.name', defaultContent: '-' },
            { data: 'department', name: 'department', defaultContent: '-' },
            { data: 'needed_by', name: 'needed_by' },
            { data: 'priority', name: 'priority' },
            { data: 'status', name: 'status' },
            { data: 'estimated_total', name: 'estimated_total', className: 'text-right' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']]
    });

    $('#filter-status').on('change', function () { table.ajax.reload(); });
});
</script>
@endpush
