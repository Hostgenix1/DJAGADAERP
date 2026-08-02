@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<div class="module-index">

    <div class="card">
        <div class="card-header border-0 d-flex justify-content-between align-items-center pt-4 pb-2" style="border-bottom: 1px solid #f0f0f0 !important;">
            <h5 class="font-weight-bold text-dark mb-0" style="font-size:1.1rem;">
                <i class="fas fa-money-check-alt mr-2 text-primary"></i>All Payments
            </h5>
            <div class="d-flex align-items-center gap-2">
                @can('create-payments')
                    <a href="{{ route('payments.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> Record Payment
                    </a>
                @endcan
                <a href="{{ route('payments.outstanding') }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Outstanding Balances
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="dt-payments" class="table table-hover" style="width:100%">
                    <thead>
                        <tr style="background:#f8f9fa; border-top: 1px solid #e9ecef; border-bottom: 2px solid #dee2e6;">
                            <th style="width:60px; font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">#</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Number</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Type</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Party</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Amount</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Method</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Paid On</th>
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
    #dt-payments thead th { white-space: nowrap; }
    #dt-payments tbody tr { transition: background .15s; }
    #dt-payments tbody tr:hover { background: #f0f4ff !important; }
    #dt-payments td { vertical-align: middle; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    $('#dt-payments').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("payments.datatable") }}',
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'number' },
            { data: 'type' },
            { data: 'party' },
            { data: 'amount' },
            { data: 'method' },
            { data: 'paid_on' },
            { data: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']]
    });
});
</script>
@endpush
