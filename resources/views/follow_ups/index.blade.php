@extends('layouts.app')

@section('title', 'Follow-ups')

{{--
  Follow-up Scheduler List - CRM Module
  Module: CRM
  Features: Server-side DataTable, follow-up task tracking, dynamic column rendering, permission-based create button, search, pagination
  Version: 2.0.0
--}}

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-calendar-check mr-1"></i> Follow-ups</h3>
                <div class="card-tools">
                    @can('create-follow_ups')
                        <a href="{{ route('follow_ups.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> New Follow-up
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <table id="dt-follow_ups" class="table table-bordered table-hover">
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

    $('#dt-follow_ups').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("follow_ups.datatable") }}',
        columns: dtCols,
        order: [[1, 'asc']]
    });
});
</script>
@endpush
