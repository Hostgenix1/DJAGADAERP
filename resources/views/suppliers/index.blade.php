@extends('layouts.app')

@section('title', 'Suppliers')

{{--
  Supplier Management List - Products Module
  Module: Products
  Features: Server-side DataTable, dynamic column rendering, permission-based create button, search, pagination, inline actions
  Version: 2.0.0
--}}

@section('content')
<div class="module-index">

    {{-- MAIN TABLE CARD --}}
    <div class="card">
        <div class="card-header border-0 d-flex justify-content-between align-items-center pt-4 pb-2" style="border-bottom: 1px solid #f0f0f0 !important;">
            <h5 class="font-weight-bold text-dark mb-0" style="font-size:1.1rem;">
                <i class="fas fa-truck mr-2 text-primary"></i>All Suppliers
            </h5>
            <div class="d-flex align-items-center gap-2">
                @can('create-suppliers')
                    <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> New Supplier
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="dt-suppliers" class="table table-hover" style="width:100%">
                    <thead>
                        <tr style="background:#f8f9fa; border-top: 1px solid #e9ecef; border-bottom: 2px solid #dee2e6;">
                            <th style="width:60px; font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">#</th>
                            @foreach($columns as $col)
                                <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">{{ $col['label'] }}</th>
                            @endforeach
                            <th style="width:140px; font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Actions</th>
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
<style>
    .module-index .card { border: none; box-shadow: 0 0 15px rgba(0,0,0,.04); border-radius: 12px; }
    #dt-suppliers thead th { white-space: nowrap; }
    #dt-suppliers tbody tr { transition: background .15s; }
    #dt-suppliers tbody tr:hover { background: #f0f4ff !important; }
    #dt-suppliers td { vertical-align: middle; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    var columns = {!! json_encode($columns) !!};
    var dtCols = [
        { data: 'DT_RowIndex', orderable: false, searchable: false }
    ];
    columns.forEach(function (c) {
        dtCols.push({ data: c.data, name: c.data });
    });
    dtCols.push({ data: 'actions', orderable: false, searchable: false });

    $('#dt-suppliers').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("suppliers.datatable") }}',
        columns: dtCols,
        order: [[1, 'asc']],
        language: { search: '', searchPlaceholder: 'Search...' }
    });
});
</script>
@endpush
