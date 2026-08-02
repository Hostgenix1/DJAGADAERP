@extends('layouts.app')

@section('title', 'Quotations')

{{--
  Quotation List - Quotations Module
  Module: Quotations
  Features: Server-side DataTable, quote number/customer/date/total/status columns, permission-based create button, search, pagination, inline actions
  Version: 1.0.0
--}}

@section('content')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Quotations</h3>
        <div class="card-tools">
            @can('create-quotes')
                <a href="{{ route('quotes.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> New Quote</a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <table id="dt-quotes" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th style="width: 40px">#</th>
                <th>Number</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total</th>
                <th>Status</th>
                <th style="width: 150px">Actions</th>
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
    $('#dt-quotes').DataTable({
        processing: true, serverSide: true,
        ajax: '{{ route("quotes.datatable") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'number', name: 'number' },
            { data: 'customer.company_name', name: 'customer.company_name' },
            { data: 'date', name: 'date' },
            { data: 'total', name: 'total' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']]
    });
});
</script>
@endpush
