@extends('layouts.app')

@section('title', 'Stock Movements')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-exchange-alt mr-1"></i> Stock Movements</h3>
                <div class="card-tools">
                    <select id="mv-warehouse" class="form-control form-control-sm d-inline-block" style="width:auto">
                        <option value="">All Warehouses</option>
                        @foreach($warehouses as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <select id="mv-product" class="form-control form-control-sm d-inline-block" style="width:auto; max-width:220px;">
                        <option value="">All Products</option>
                        @foreach($products as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <select id="mv-type" class="form-control form-control-sm d-inline-block" style="width:auto">
                        <option value="">All Types</option>
                        <option value="in">In</option>
                        <option value="out">Out</option>
                        <option value="adjustment">Adjustment</option>
                    </select>
                    @can('create-stock-movements')
                        <a href="{{ route('stock.movements.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Record Movement</a>
                    @endcan
                </div>
            </div>
            <div class="card-body p-0">
                <table id="dt-movements" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Warehouse</th>
                            <th>Type</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Unit Cost</th>
                            <th>Reference</th>
                            <th>By</th>
                            <th style="width:90px">Actions</th>
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
    var table = $('#dt-movements').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("stock.movements.datatable") }}',
            data: function (d) {
                d.warehouse_id = $('#mv-warehouse').val();
                d.product_id = $('#mv-product').val();
                d.type = $('#mv-type').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'movement_date' },
            { data: 'product_id' },
            { data: 'warehouse_id' },
            { data: 'type' },
            { data: 'qty', className: 'text-right' },
            { data: 'unit_cost', className: 'text-right', searchable: false },
            { data: 'reference' },
            { data: 'created_by', orderable: false, searchable: false },
            { data: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']]
    });
    $('#mv-warehouse, #mv-product, #mv-type').on('change', function () { table.ajax.reload(); });
});
</script>
@endpush
