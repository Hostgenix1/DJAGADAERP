@extends('layouts.app')

@section('title', 'Orders')

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-shopping-cart"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Orders</span>
                <span class="info-box-number">{{ $totalOrders }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-secondary"><i class="fas fa-pencil-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Draft</span>
                <span class="info-box-number">{{ $draft }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Confirmed</span>
                <span class="info-box-number">{{ $confirmed }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-cogs"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Processing</span>
                <span class="info-box-number">{{ $processing }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-check-double"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Completed</span>
                <span class="info-box-number">{{ $completed }}</span>
            </div>
        </div>
    </div>
</div>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-shopping-cart mr-1"></i> All Orders</h3>
        <div class="card-tools">
            @can('create-orders')
                <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> New Order
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
                <a class="nav-link filter-tab" href="#" data-status="draft">
                    <i class="fas fa-pencil-alt mr-1"></i> Draft
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-tab" href="#" data-status="confirmed">
                    <i class="fas fa-check mr-1"></i> Confirmed
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-tab" href="#" data-status="processing">
                    <i class="fas fa-cogs mr-1"></i> Processing
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-tab" href="#" data-status="completed">
                    <i class="fas fa-check-double mr-1"></i> Completed
                </a>
            </li>
        </ul>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="dt-orders" class="table table-bordered table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th>Number</th>
                        <th>Customer</th>
                        <th>Order Date</th>
                        <th>Expected Delivery</th>
                        <th class="text-right">Total</th>
                        <th>Status</th>
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
    var table = $('#dt-orders').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("orders.datatable") }}',
            data: function (d) {
                d.status = activeFilter;
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'number' },
            { data: 'customer.company_name', name: 'customer.company_name', defaultContent: '-' },
            { data: 'order_date' },
            { data: 'expected_delivery', defaultContent: '-' },
            { data: 'total', className: 'text-right' },
            { data: 'status' },
            { data: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']],
        language: { search: '', searchPlaceholder: 'Search orders...' },
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
