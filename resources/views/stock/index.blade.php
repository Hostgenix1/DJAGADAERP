@extends('layouts.app')

@section('title', 'Stock Levels')

@section('content')
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner"><h3>{{ $totalProducts }}</h3><p>Active Products</p></div>
            <div class="icon"><i class="fas fa-boxes"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-danger">
            <div class="inner"><h3>{{ $lowStock }}</h3><p>Low Stock Items</p></div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner"><h3>{{ number_format($stockValue, 2) }}</h3><p>Stock Value (at cost)</p></div>
            <div class="icon"><i class="fas fa-coins"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-boxes mr-1"></i> Stock Levels</h3>
                <div class="card-tools">
                    <select id="warehouse-filter" class="form-control form-control-sm d-inline-block" style="width:auto">
                        <option value="">All Warehouses</option>
                        @foreach($warehouses as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <div class="custom-control custom-checkbox d-inline-block ml-2 mr-2">
                        <input type="checkbox" id="low-filter" class="custom-control-input">
                        <label class="custom-control-label" for="low-filter">Low stock only</label>
                    </div>
                    @can('create-stock-movements')
                        <a href="{{ route('stock.movements.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Record Movement</a>
                    @endcan
                </div>
            </div>
            <div class="card-body p-0">
                <table id="dt-stock" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Product</th>
                            <th>Category / Brand</th>
                            <th class="text-right">Stock</th>
                            <th class="text-right">Reorder Level</th>
                            <th class="text-right">Buy Price</th>
                            <th class="text-right">Stock Value</th>
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
    var table = $('#dt-stock').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("stock.datatable") }}',
            data: function (d) {
                d.warehouse_id = $('#warehouse-filter').val();
                d.low_only = $('#low-filter').is(':checked') ? 1 : 0;
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name' },
            { data: 'category', orderable: false, searchable: false },
            { data: 'stock_total', className: 'text-right', searchable: false },
            { data: 'reorder_level', className: 'text-right', searchable: false },
            { data: 'buy_price', className: 'text-right', searchable: false },
            { data: 'value', className: 'text-right', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']]
    });
    $('#warehouse-filter, #low-filter').on('change', function () { table.ajax.reload(); });
});
</script>
@endpush
