@extends('layouts.app')

@section('title', 'Purchase Orders')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h4 class="mb-0"><i class="fas fa-clipboard-list mr-1"></i> Purchase Orders</h4>
            </div>
            <div class="col-sm-6 text-right">
                @can('create-purchase-orders')
                    <a href="{{ route('purchase_orders.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> New Purchase Order</a>
                @endcan
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box"><span class="info-box-icon bg-secondary"><i class="fas fa-file-alt"></i></span>
                    <div class="info-box-content"><span class="info-box-text">Total POs</span><span class="info-box-number">{{ $totalPos }}</span></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box"><span class="info-box-icon bg-info"><i class="fas fa-truck-loading"></i></span>
                    <div class="info-box-content"><span class="info-box-text">Pending (Draft/Confirmed)</span><span class="info-box-number">{{ $defaultCurrency?->symbol ?? '$' }} {{ number_format($pending, 2) }}</span></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box"><span class="info-box-icon bg-primary"><i class="fas fa-box-open"></i></span>
                    <div class="info-box-content"><span class="info-box-text">Received</span><span class="info-box-number">{{ $defaultCurrency?->symbol ?? '$' }} {{ number_format($received, 2) }}</span></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box"><span class="info-box-icon bg-success"><i class="fas fa-money-bill-wave"></i></span>
                    <div class="info-box-content"><span class="info-box-text">Total (excl. cancelled)</span><span class="info-box-number">{{ $defaultCurrency?->symbol ?? '$' }} {{ number_format($totalAmount, 2) }}</span></div>
                </div>
            </div>
        </div>

        @if(!empty($poByCurrency))
        <div class="card card-outline card-info mb-3">
            <div class="card-header py-2"><h5 class="card-title mb-0"><i class="fas fa-coins mr-1"></i> Purchase Orders by Currency</h5></div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead><tr><th>Currency</th><th class="text-right">Count</th><th class="text-right">Total</th></tr></thead>
                    <tbody>
                    @foreach($poByCurrency as $row)
                        <tr><td><strong>{{ $row['code'] }}</strong> <span class="text-muted">{{ $row['symbol'] }}</span></td><td class="text-right">{{ $row['count'] }}</td><td class="text-right">{{ number_format($row['total'], 2) }}</td></tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">All Purchase Orders</h3></div>
            <div class="card-body">
                <table id="dt-pos" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Number</th>
                            <th>Supplier</th>
                            <th>Date</th>
                            <th>Expected Delivery</th>
                            <th>Status</th>
                            <th>Currency</th>
                            <th class="text-right">Total</th>
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
    $('#dt-pos').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("purchase_orders.datatable") }}',
            data: function (d) { d.status = $('#filter-status').val(); }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'number', name: 'number' },
            { data: 'supplier.company_name', name: 'supplier.company_name' },
            { data: 'po_date', name: 'po_date' },
            { data: 'expected_delivery', name: 'expected_delivery' },
            { data: 'status', name: 'status' },
            { data: 'currency.code', name: 'currency.code' },
            { data: 'total', name: 'total', className: 'text-right' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']]
    });
});
</script>
@endpush
