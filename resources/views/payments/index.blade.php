@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        @can('create-payments')
            <a href="{{ route('payments.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Record Payment</a>
        @endcan
        <a href="{{ route('payments.outstanding') }}" class="btn btn-warning"><i class="fas fa-exclamation-triangle"></i> Outstanding Balances</a>
    </div>
</div>

<div class="card card-secondary">
    <div class="card-header"><h3 class="card-title">Payment History</h3></div>
    <div class="card-body">
        <table id="dt-payments" class="table table-bordered table-hover">
            <thead><tr><th>#</th><th>Number</th><th>Type</th><th>Party</th><th>Amount</th><th>Method</th><th>Date</th><th style="width:80px">Actions</th></tr></thead>
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
$(function(){
    $('#dt-payments').DataTable({
        processing:true, serverSide:true,
        ajax:'{{ route("payments.datatable") }}',
        columns:[
            {data:'DT_RowIndex',orderable:false,searchable:false},
            {data:'number'},{data:'type'},{data:'party'},{data:'amount'},
            {data:'method'},{data:'paid_on'},
            {data:'actions',orderable:false,searchable:false}
        ],
        order:[[1,'desc']]
    });
});
</script>
@endpush
