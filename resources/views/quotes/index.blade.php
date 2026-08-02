@extends('layouts.app')

@section('title', 'Quotations')

{{--
  Quotation List - Quotations Module
  Module: Quotations
  Features: KPI cards (total, amount, accepted, pending), status filter tabs, server-side DataTable with quote number/customer/date/total/status/actions, permission-based create button, search, pagination
  Version: 1.1.0
--}}

@section('content')
<div class="quote-index">

    {{-- KPI CARDS --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card card-block card-stretch card-height">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="font-weight-bold text-dark mb-1">{{ $totalQuotes }}</h4>
                            <p class="text-muted mb-0">Total Quotes</p>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;background:rgba(0,123,255,.1);">
                            <i class="fas fa-file-alt text-primary" style="font-size:1.4rem;"></i>
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
                            <p class="text-muted mb-0">Total Value</p>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;background:rgba(23,162,184,.1);">
                            <i class="fas fa-dollar-sign text-info" style="font-size:1.4rem;"></i>
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
                            <h4 class="font-weight-bold text-success mb-1">${{ number_format($accepted, 2) }}</h4>
                            <p class="text-muted mb-0">Accepted</p>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;background:rgba(40,167,69,.1);">
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
                            <h4 class="font-weight-bold text-warning mb-1">${{ number_format($pending, 2) }}</h4>
                            <p class="text-muted mb-0">Pending</p>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:56px;height:56px;background:rgba(255,193,7,.1);">
                            <i class="fas fa-clock text-warning" style="font-size:1.4rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- QUOTES TABLE --}}
    <div class="card">
        <div class="card-header border-0 d-flex justify-content-between align-items-center pt-4 pb-2" style="border-bottom: 1px solid #f0f0f0 !important;">
            <h5 class="font-weight-bold text-dark mb-0" style="font-size:1.1rem;">
                <i class="fas fa-file-alt mr-2 text-primary"></i>All Quotations
            </h5>
            <div class="d-flex align-items-center gap-2">
                @can('create-quotes')
                    <a href="{{ route('quotes.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus mr-1"></i> New Quote
                    </a>
                @endcan
            </div>
        </div>

        {{-- STATUS FILTER TABS --}}
        <div class="px-4 pt-3">
            <ul class="nav nav-pills gap-2">
                <li class="nav-item">
                    <a class="nav-link active filter-tab" href="#" data-status="all">
                        All <span class="badge badge-light ml-1" id="count-all">-</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link filter-tab" href="#" data-status="draft" style="border: 1px solid #dee2e6;">
                        <i class="fas fa-pencil-alt mr-1 text-secondary"></i> Draft
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link filter-tab" href="#" data-status="pending" style="border: 1px solid #dee2e6;">
                        <i class="fas fa-clock mr-1 text-warning"></i> Pending
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link filter-tab" href="#" data-status="sent" style="border: 1px solid #dee2e6;">
                        <i class="fas fa-paper-plane mr-1 text-info"></i> Sent
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link filter-tab" href="#" data-status="accepted" style="border: 1px solid #dee2e6;">
                        <i class="fas fa-check mr-1 text-success"></i> Accepted
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link filter-tab" href="#" data-status="rejected" style="border: 1px solid #dee2e6;">
                        <i class="fas fa-times mr-1 text-danger"></i> Rejected
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link filter-tab" href="#" data-status="expired" style="border: 1px solid #dee2e6;">
                        <i class="fas fa-hourglass-end mr-1 text-dark"></i> Expired
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="dt-quotes" class="table table-hover" style="width:100%">
                    <thead>
                        <tr style="background:#f8f9fa; border-top: 1px solid #e9ecef; border-bottom: 2px solid #dee2e6;">
                            <th style="width:60px; font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">#</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Number</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Customer</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Date</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Valid Until</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px; text-align:right;">Total</th>
                            <th style="font-size:0.82rem; font-weight:600; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Status</th>
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
    .quote-index .card { border: none; box-shadow: 0 0 15px rgba(0,0,0,.04); border-radius: 12px; }
    .filter-tab.active { background: #007bff !important; color: #fff !important; border-color: #007bff !important; }
    .filter-tab:hover:not(.active) { background: #e9ecef; }
    #dt-quotes thead th { white-space: nowrap; }
    #dt-quotes tbody tr { transition: background .15s; }
    #dt-quotes tbody tr:hover { background: #f0f4ff !important; }
    #dt-quotes td { vertical-align: middle; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    var table = $('#dt-quotes').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("quotes.datatable") }}',
            data: function (d) {
                d.status = activeFilter;
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'number' },
            { data: 'customer.company_name', name: 'customer.company_name', defaultContent: '-' },
            { data: 'date' },
            { data: 'valid_until', defaultContent: '-' },
            { data: 'total', className: 'text-right' },
            { data: 'status' },
            { data: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']],
        language: { search: '', searchPlaceholder: 'Search quotes...' },
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
        activeFilter = $(this).data('status') === 'all' ? '' : $(this).data('status');
        table.ajax.reload();
    });
});
</script>
@endpush
