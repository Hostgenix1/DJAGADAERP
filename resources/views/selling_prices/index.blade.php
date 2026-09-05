@extends('layouts.app')

@section('title', 'Selling Prices')

@section('content')
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner"><h3>{{ $approved }}</h3><p>Approved &amp; Valid</p></div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-secondary">
            <div class="inner"><h3>{{ $expired }}</h3><p>Expired</p></div>
            <div class="icon"><i class="fas fa-hourglass-end"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner"><h3>{{ $aiReady }}</h3><p>Approved for AI</p></div>
            <div class="icon"><i class="fas fa-robot"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-dollar-sign mr-1"></i> Selling Prices</h3>
                <div class="card-tools">
                    @can('create-selling-prices')
                        <a href="{{ route('selling_prices.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Selling Price</a>
                    @endcan
                </div>
            </div>
            <div class="card-body p-0">
                <table id="dt-selling-prices" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Packaging</th>
                            @can('view-pricing-costs')
                                <th class="text-right">Cost</th>
                                <th class="text-right">Margin</th>
                            @endcan
                            <th class="text-right">Selling Price</th>
                            <th>Cur</th>
                            <th>Incoterm</th>
                            <th>Destination</th>
                            <th class="text-right">MOQ</th>
                            <th>Valid Until</th>
                            <th>Status</th>
                            <th>AI</th>
                            <th style="width:130px">Actions</th>
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
    const cols = [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'customer_id', name: 'customer_id' },
        { data: 'product_id', name: 'product_id' },
        { data: 'packaging', name: 'packaging' },
        @can('view-pricing-costs')
        { data: 'supplier_cost', name: 'supplier_cost', className: 'text-right' },
        { data: 'margin', name: 'margin', className: 'text-right', orderable: false, searchable: false },
        @endcan
        { data: 'selling_price', name: 'selling_price', className: 'text-right' },
        { data: 'currency_id', name: 'currency_id' },
        { data: 'incoterm', name: 'incoterm' },
        { data: 'destination', name: 'destination' },
        { data: 'min_qty', name: 'min_qty', className: 'text-right' },
        { data: 'valid_until', name: 'valid_until' },
        { data: 'status', name: 'status' },
        { data: 'approved_for_ai', name: 'approved_for_ai', orderable: false, searchable: false },
        { data: 'actions', name: 'actions', orderable: false, searchable: false }
    ];
    $('#dt-selling-prices').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("selling_prices.datatable") }}',
        columns: cols,
        order: [[1, 'asc']]
    });
});
</script>
@endpush
