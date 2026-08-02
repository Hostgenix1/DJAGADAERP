@extends('layouts.app')

@section('title', 'Shipments')

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-shipping-fast"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Shipments</span>
                <span class="info-box-number">{{ $total }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-secondary"><i class="fas fa-box"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Preparing</span>
                <span class="info-box-number">{{ $preparing }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-truck"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">In Transit</span>
                <span class="info-box-number">{{ $inTransit }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Delivered</span>
                <span class="info-box-number">{{ $delivered }}</span>
            </div>
        </div>
    </div>
</div>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-shipping-fast mr-1"></i> All Shipments</h3>
        <div class="card-tools">
            @can('create-shipments')
                <a href="{{ route('shipments.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> New Shipment
                </a>
            @endcan
        </div>
    </div>

    <div class="card-body">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active filter-tab" href="#" data-status="all">
                    All <span class="badge badge-light ml-1" id="count-all">-</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-tab" href="#" data-status="preparing">
                    <i class="fas fa-box mr-1"></i> Preparing
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-tab" href="#" data-status="in_transit">
                    <i class="fas fa-truck mr-1"></i> In Transit
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-tab" href="#" data-status="customs">
                    <i class="fas fa-passport mr-1"></i> Customs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-tab" href="#" data-status="delivered">
                    <i class="fas fa-check-circle mr-1"></i> Delivered
                </a>
            </li>
        </ul>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="dt-shipments" class="table table-bordered table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th>Number</th>
                        <th>Customer</th>
                        <th>Carrier</th>
                        <th>Tracking #</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Shipped At</th>
                        <th>Est. Arrival</th>
                        <th style="width:120px">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
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
    var table = $('#dt-shipments').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("shipments.datatable") }}',
            data: function (d) {
                d.status = activeFilter;
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'number' },
            { data: 'customer.company_name', name: 'customer.company_name', defaultContent: '-' },
            { data: 'carrier' },
            { data: 'tracking_number' },
            { data: 'shipping_method' },
            { data: 'status' },
            { data: 'shipped_at' },
            { data: 'estimated_arrival' },
            { data: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']],
        language: { search: '', searchPlaceholder: 'Search shipments...' },
        drawCallback: function (settings) {
            if (settings.json && settings.json.recordsTotal !== undefined) {
                $('#count-all').text(settings.json.recordsTotal);
            }
        }
    });

    var activeFilter = '';
    $('.filter-tab').on('click', function (e) {
        e.preventDefault();
        $('.filter-tab').removeClass('active');
        $(this).addClass('active');
        activeFilter = $(this).data('status') === 'all' ? '' : $(this).data('status');
        table.ajax.reload();
    });
});
</script>
@endpush
