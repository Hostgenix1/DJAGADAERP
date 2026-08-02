@extends('layouts.app')

@section('title', 'Products')

{{--
  Product Catalog - Products Module
  Module: Products
  Features: Server-side DataTable, dynamic column rendering, permission-based create button, search, pagination, inline actions
  Version: 2.0.0
--}}

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-boxes mr-1"></i> Products</h3>
                <div class="card-tools">
                    @can('create-products')
                        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> New Product
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <table id="dt-products" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            @foreach($columns as $col)
                                <th>{{ $col['label'] }}</th>
                            @endforeach
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
    var columns = {!! json_encode($columns) !!};
    var dtCols = [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false }
    ];
    columns.forEach(function (c) {
        dtCols.push({ data: c.data, name: c.data });
    });
    dtCols.push({ data: 'actions', name: 'actions', orderable: false, searchable: false });

    $('#dt-products').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("products.datatable") }}',
        columns: dtCols,
        order: [[1, 'asc']]
    });
});
</script>
@endpush
