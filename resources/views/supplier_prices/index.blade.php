@extends('layouts.app')

@section('title', 'Supplier Prices')

@section('content')
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner"><h3>{{ $total }}</h3><p>Recorded Offers</p></div>
            <div class="icon"><i class="fas fa-truck-loading"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner"><h3>{{ $active }}</h3><p>Currently Valid</p></div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-dark">
            <div class="inner"><h3>{{ $suppliers }}</h3><p>Suppliers Quoted</p></div>
            <div class="icon"><i class="fas fa-industry"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-truck-loading mr-1"></i> Supplier Prices <span class="badge badge-dark ml-1">Internal / Confidential</span></h3>
                <div class="card-tools">
                    @can('create-supplier-prices')
                        <a href="{{ route('supplier_prices.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Record Offer</a>
                    @endcan
                </div>
            </div>
            <div class="card-body p-0">
                <table id="dt-supplier-prices" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Received</th>
                            <th>Supplier</th>
                            <th>Product</th>
                            <th>Packaging</th>
                            <th>Origin</th>
                            <th class="text-right">Price</th>
                            <th>Cur</th>
                            <th>Incoterm</th>
                            <th>Destination</th>
                            <th class="text-right">Qty</th>
                            <th>Container</th>
                            <th>Valid Until</th>
                            <th>Source</th>
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
    $('#dt-supplier-prices').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("supplier_prices.datatable") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'date_received', name: 'date_received' },
            { data: 'supplier_id', name: 'supplier_id' },
            { data: 'product_id', name: 'product_id' },
            { data: 'packaging', name: 'packaging' },
            { data: 'origin', name: 'origin' },
            { data: 'supplier_price', name: 'supplier_price', className: 'text-right' },
            { data: 'currency_id', name: 'currency_id' },
            { data: 'incoterm', name: 'incoterm' },
            { data: 'destination_port', name: 'destination_port' },
            { data: 'quantity', name: 'quantity', className: 'text-right' },
            { data: 'container_type', name: 'container_type' },
            { data: 'valid_until', name: 'valid_until' },
            { data: 'source', name: 'source' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']]
    });
});
</script>
@endpush
