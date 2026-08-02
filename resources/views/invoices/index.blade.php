@extends('layouts.app')

@section('title', 'Invoices')

{{--
  Invoice List - Invoices Module
  Module: Invoices
  Features: KPI cards (total, paid, outstanding), type filter tabs, server-side DataTable with invoice number/type/customer/date/due/total/balance/status/actions, permission-based create button, search, pagination
  Version: 1.1.0
--}}

@section('content')
<div class="invoice-index">

    {{-- KPI CARDS --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-1">{{ $totalInvoices }}</h4>
                            <p class="text-muted mb-0">Total Invoices</p>
                        </div>
                        <div class="rounded-circle bg-primary-light d-flex align-items-center justify-content-center" style="width:56px;height:56px;">
                            <i class="fas fa-file-invoice-dollar text-primary" style="font-size:1.4rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-1">${{ number_format($totalAmount, 2) }}</h4>
                            <p class="text-muted mb-0">Total Amount</p>
                        </div>
                        <div class="rounded-circle bg-success-light d-flex align-items-center justify-content-center" style="width:56px;height:56px;">
                            <i class="fas fa-dollar-sign text-success" style="font-size:1.4rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-bold text-success mb-1">${{ number_format($totalPaid, 2) }}</h4>
                            <p class="text-muted mb-0">Paid</p>
                        </div>
                        <div class="rounded-circle bg-success-light d-flex align-items-center justify-content-center" style="width:56px;height:56px;">
                            <i class="fas fa-check-circle text-success" style="font-size:1.4rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-bold text-danger mb-1">${{ number_format($totalOutstanding, 2) }}</h4>
                            <p class="text-muted mb-0">Outstanding</p>
                        </div>
                        <div class="rounded-circle bg-danger-light d-flex align-items-center justify-content-center" style="width:56px;height:56px;">
                            <i class="fas fa-exclamation-triangle text-danger" style="font-size:1.4rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- INVOICES TABLE --}}
    <div class="card">
        <div class="card-header border-0 d-flex justify-content-between align-items-center pt-4 pb-2" style="border-bottom: 1px solid #f0f0f0 !important;">
            <h5 class="font-weight-bold text-dark mb-0" style="font-size:1.1rem;">
                <i class="fas fa-file-invoice mr-2 text-primary"></i>All Invoices
            </h5>
            <div class="d-flex align-items-center gap-2">
                @can('create-invoices')
                    <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> New Invoice
                    </a>
                @endcan
            </div>
        </div>

        {{-- TYPE FILTER TABS --}}
        <div class="px-4 pt-3">
            <ul class="nav nav-pills gap-2">
                <li class="nav-item">
                    <a class="nav-link active filter-tab" href="#" data-type="all">
                        All <span class="badge badge-light ml-1" id="count-all">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link filter-tab" href="#" data-type="commercial" style="border: 1px solid #dee2e6;">
                        <i class="fas fa-file-invoice mr-1 text-primary"></i> Commercial
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link filter-tab" href="#" data-type="proforma" style="border: 1px solid #dee2e6;">
                        <i class="fas fa-clipboard-list mr-1 text-info"></i> Proforma
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link filter-tab" href="#" data-type="credit_note" style="border: 1px solid #dee2e6;">
                        <i class="fas fa-undo mr-1 text-warning"></i> Credit Note
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link filter-tab" href="#" data-type="packing_list" style="border: 1px solid #dee2e6;">
                        <i class="fas fa-box mr-1 text-secondary"></i> Packing List
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link filter-tab" href="#" data-type="delivery_note" style="border: 1px solid #dee2e6;">
                        <i class="fas fa-truck mr-1 text-success"></i> Delivery Note
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="dt-invoices" class="table table-hover" style="width:100%">
                    <thead>
                        <tr style="background:#f8f9fa; border-top: 1px solid #e9ecef; border-bottom: 2px solid #dee2e6;">
                            <th style="width:60px; font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">#</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Number</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Type</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Customer</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Date</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Due Date</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px; text-align:right;">Total</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px; text-align:right;">Balance</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Status</th>
                            <th style="width:120px; font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Actions</th>
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
    .bg-primary-light { background: rgba(0,123,255,.1); }
    .bg-success-light { background: rgba(40,167,69,.1); }
    .bg-danger-light  { background: rgba(220,53,69,.1); }
    .invoice-index .card { border: none; box-shadow: 0 0 15px rgba(0,0,0,.04); border-radius: 12px; }
    .filter-tab.active { background: #007bff !important; color: #fff !important; border-color: #007bff !important; }
    .filter-tab:hover:not(.active) { background: #e9ecef; }
    #dt-invoices thead th { white-space: nowrap; }
    #dt-invoices tbody tr { transition: background .15s; }
    #dt-invoices tbody tr:hover { background: #f0f4ff !important; }
    #dt-invoices td { vertical-align: middle; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    var table = $('#dt-invoices').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("invoices.datatable") }}',
            data: function (d) {
                d.type = activeFilter;
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'number' },
            { data: 'type' },
            { data: 'customer.company_name', name: 'customer.company_name', defaultContent: '-' },
            { data: 'invoice_date' },
            { data: 'due_date' },
            { data: 'total', className: 'text-right' },
            { data: 'balance', className: 'text-right' },
            { data: 'status' },
            { data: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']],
        language: { search: '', searchPlaceholder: 'Search invoices...' },
        drawCallback: function (settings) {
            if (settings.json && settings.json.recordsTotal !== undefined) {
                $('#count-all').text(settings.json.recordsTotal);
            }
        }
    });

    var activeFilter = '';
    $('.filter-tab').on('click', function (e) {
        e.preventDefault();
        $('.filter-tab').removeClass('active').css('border', '1px solid #dee2e6');
        $(this).addClass('active').css('border', 'none');
        activeFilter = $(this).data('type') === 'all' ? '' : $(this).data('type');
        table.ajax.reload();
    });
});
</script>
@endpush
