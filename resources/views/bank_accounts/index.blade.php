@extends('layouts.app')

@section('title', 'Bank Accounts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-university mr-1"></i> Bank Accounts</h3>
                <div class="card-tools">
                    @can('create-bank-accounts')
                        <a href="{{ route('bank-accounts.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> New Bank Account
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <table id="dt-bank-accounts" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Bank Name</th>
                            <th>Account Name</th>
                            <th>Account Number</th>
                            <th>IBAN</th>
                            <th>SWIFT</th>
                            <th>Currency</th>
                            <th>Default</th>
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
    $('#dt-bank-accounts').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("bank-accounts.datatable") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'bank_name', name: 'bank_name' },
            { data: 'account_name', name: 'account_name' },
            { data: 'account_number', name: 'account_number' },
            { data: 'iban', name: 'iban' },
            { data: 'swift_code', name: 'swift_code' },
            { data: 'currency', name: 'currency', searchable: false, orderable: false },
            { data: 'is_default', name: 'is_default' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']]
    });
});
</script>
@endpush
