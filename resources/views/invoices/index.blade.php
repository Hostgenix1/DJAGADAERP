@extends('layouts.app')

@section('title', 'Invoices')

{{--
  Invoice List - Invoices Module
  Module: Invoices
  Features: KPI cards (total, paid, outstanding), type filter tabs, server-side DataTable with invoice number/type/customer/date/due/total/balance/status/actions, permission-based create button, search, pagination
  Version: 1.2.0
--}}

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-file-invoice-dollar"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Invoices</span>
                <span class="info-box-number">{{ $totalInvoices }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Amount</span>
                <span class="info-box-number">${{ number_format($totalAmount, 2) }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Paid</span>
                <span class="info-box-number">${{ number_format($totalPaid, 2) }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Outstanding</span>
                <span class="info-box-number">${{ number_format($totalOutstanding, 2) }}</span>
            </div>
        </div>
    </div>
</div>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-file-invoice mr-1"></i> All Invoices</h3>
        <div class="card-tools">
            @can('create-invoices')
                <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> New Invoice
                </a>
            @endcan
        </div>
    </div>

    <div class="card-body">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active filter-tab" href="#" data-type="all">
                    All <span class="badge badge-light ml-1" id="count-all">-</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-tab" href="#" data-type="commercial">
                    <i class="fas fa-file-invoice mr-1"></i> Commercial
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-tab" href="#" data-type="proforma">
                    <i class="fas fa-clipboard-list mr-1"></i> Proforma
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-tab" href="#" data-type="credit_note">
                    <i class="fas fa-undo mr-1"></i> Credit Note
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-tab" href="#" data-type="packing_list">
                    <i class="fas fa-box mr-1"></i> Packing List
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-tab" href="#" data-type="delivery_note">
                    <i class="fas fa-truck mr-1"></i> Delivery Note
                </a>
            </li>
        </ul>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="dt-invoices" class="table table-bordered table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th>Number</th>
                        <th>Type</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Due Date</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">Balance</th>
                        <th>Status</th>
                        <th style="width:120px">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
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
        $('.filter-tab').removeClass('active');
        $(this).addClass('active');
        activeFilter = $(this).data('type') === 'all' ? '' : $(this).data('type');
        table.ajax.reload();
    });
});
</script>
@endpush
