@extends('layouts.app')

@section('title', 'Quotations')

{{--
  Quotation List - Quotations Module
  Module: Quotations
  Features: KPI cards (total, amount, accepted, pending), status filter tabs, server-side DataTable with quote number/customer/date/total/status/actions, permission-based create button, search, pagination
  Version: 1.2.0
--}}

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-file-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Quotes</span>
                <span class="info-box-number">{{ $totalQuotes }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-dollar-sign"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Value</span>
                <span class="info-box-number">${{ number_format($totalAmount, 2) }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Accepted</span>
                <span class="info-box-number">${{ number_format($accepted, 2) }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pending</span>
                <span class="info-box-number">${{ number_format($pending, 2) }}</span>
            </div>
        </div>
    </div>
</div>

<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-file-alt mr-1"></i> All Quotations</h3>
        <div class="card-tools">
            @can('create-quotes')
                <a href="{{ route('quotes.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> New Quote
                </a>
            @endcan
        </div>
    </div>

    <div class="card-body">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active filter-tab" href="#" data-status="all">
                    All <span class="badge badge-light ml-1" id="count-all">-</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-tab" href="#" data-status="draft">
                    <i class="fas fa-pencil-alt mr-1"></i> Draft
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-tab" href="#" data-status="pending">
                    <i class="fas fa-clock mr-1"></i> Pending
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-tab" href="#" data-status="sent">
                    <i class="fas fa-paper-plane mr-1"></i> Sent
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-tab" href="#" data-status="accepted">
                    <i class="fas fa-check mr-1"></i> Accepted
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-tab" href="#" data-status="rejected">
                    <i class="fas fa-times mr-1"></i> Rejected
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link filter-tab" href="#" data-status="expired">
                    <i class="fas fa-hourglass-end mr-1"></i> Expired
                </a>
            </li>
        </ul>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="dt-quotes" class="table table-bordered table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th>Number</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Valid Until</th>
                        <th class="text-right">Total</th>
                        <th>Status</th>
                        <th style="width:140px">Actions</th>
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
        $('.filter-tab').removeClass('active');
        $(this).addClass('active');
        activeFilter = $(this).data('status') === 'all' ? '' : $(this).data('status');
        table.ajax.reload();
    });
});
</script>
@endpush
