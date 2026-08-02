@extends('layouts.app')

@section('title', 'Invoices')

{{--
  Invoice List - Invoices Module
  Module: Invoices
  Features: Server-side DataTable, invoice number/type/customer/date/due/total/balance/status columns, permission-based create button, search, pagination, inline actions
  Version: 1.0.0
--}}

@section('content')
<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">Invoices</h3>
        <div class="card-tools">
            @can('create-invoices')
                <a href="{{ route('invoices.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> New Invoice</a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <table id="dt-invoices" class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>#</th><th>Number</th><th>Type</th><th>Customer</th><th>Date</th><th>Due</th><th>Total</th><th>Balance</th><th>Status</th><th style="width:150px">Actions</th>
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
    $('#dt-invoices').DataTable({
        processing: true, serverSide: true,
        ajax: '{{ route("invoices.datatable") }}',
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'number' },
            { data: 'type' },
            { data: 'customer.company_name', name: 'customer.company_name' },
            { data: 'invoice_date' },
            { data: 'due_date' },
            { data: 'total' },
            { data: 'balance' },
            { data: 'status' },
            { data: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']]
    });
});
</script>
@endpush
