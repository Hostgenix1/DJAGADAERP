@extends('layouts.app')

@section('title', 'Currency')

@section('content')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Currency</h3>
        <div class="card-tools">
            @can('create-currencies')
                <a href="{{ route('currencies.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> New</a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <table id="dt-currencies" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th style="width: 40px">#</th>
                @foreach($columns as $col)
                    <th>{{ $col['label'] }}</th>
                @endforeach
                <th style="width: 120px">Actions</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
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

        $('#dt-currencies').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('currencies.datatable') }}',
            columns: dtCols,
            order: [[1, 'asc']]
        });
    });
</script>
@endpush
