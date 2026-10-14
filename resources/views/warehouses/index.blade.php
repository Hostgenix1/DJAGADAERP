@extends('layouts.app')

@section('title', 'Warehouses')

@section('content')
<div class="row">
    <div class="col-lg-6 col-6">
        <div class="small-box bg-info">
            <div class="inner"><h3>{{ $total }}</h3><p>Total Warehouses</p></div>
            <div class="icon"><i class="fas fa-warehouse"></i></div>
        </div>
    </div>
    <div class="col-lg-6 col-6">
        <div class="small-box bg-success">
            <div class="inner"><h3>{{ $active }}</h3><p>Active</p></div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-warehouse mr-1"></i> Warehouses</h3>
                <div class="card-tools">
                    @can('create-warehouses')
                        <a href="{{ route('warehouses.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Warehouse</a>
                    @endcan
                </div>
            </div>
            <div class="card-body p-0">
                <table id="dt-warehouses" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Location</th>
                            <th class="text-right">Products</th>
                            <th>Status</th>
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
    $('#dt-warehouses').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("warehouses.datatable") }}',
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name' },
            { data: 'code' },
            { data: 'location' },
            { data: 'products_count', className: 'text-right', orderable: false, searchable: false },
            { data: 'is_active' },
            { data: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']]
    });
});
</script>
@endpush
