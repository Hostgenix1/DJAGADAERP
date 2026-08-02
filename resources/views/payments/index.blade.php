@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<div class="container-fluid">

<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-money-check-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Payments</span>
                <span class="info-box-number">{{ $totalPayments }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-arrow-down"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Received</span>
                <span class="info-box-number">{{ $defaultCurrency?->symbol ?? '$' }}{{ number_format($totalReceived, 2) }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-arrow-up"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Paid Out</span>
                <span class="info-box-number">{{ $defaultCurrency?->symbol ?? '$' }}{{ number_format($totalPaid, 2) }}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-{{ $netCash >= 0 ? 'success' : 'danger' }}"><i class="fas fa-balance-scale"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Net Cash</span>
                <span class="info-box-number">{{ $defaultCurrency?->symbol ?? '$' }}{{ number_format($netCash, 2) }}</span>
            </div>
        </div>
    </div>
</div>

@if(count($paymentByCurrency) > 0)
<div class="row mb-3">
    <div class="col-12">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-coins mr-1"></i> Payments by Currency</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Currency</th>
                            <th class="text-right">Received</th>
                            <th class="text-right">Paid Out</th>
                            <th class="text-right">Net</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                        $grouped = [];
                        foreach ($paymentByCurrency as $pb) {
                            $grouped[$pb['code']]['code'] = $pb['code'];
                            $grouped[$pb['code']]['symbol'] = $pb['symbol'];
                            $grouped[$pb['code']][$pb['type']] = $pb['total'];
                        }
                    @endphp
                    @foreach($grouped as $g)
                        <tr>
                            <td><strong>{{ $g['code'] }}</strong> <span class="text-muted">({{ $g['symbol'] }})</span></td>
                            <td class="text-right text-success">{{ $g['symbol'] }}{{ number_format($g['customer'] ?? 0, 2) }}</td>
                            <td class="text-right text-warning">{{ $g['symbol'] }}{{ number_format($g['supplier'] ?? 0, 2) }}</td>
                            <td class="text-right text-{{ (($g['customer'] ?? 0) - ($g['supplier'] ?? 0)) >= 0 ? 'success' : 'danger' }}">
                                {{ $g['symbol'] }}{{ number_format(($g['customer'] ?? 0) - ($g['supplier'] ?? 0), 2) }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Payments</h1>
        </div>
        <div class="col-sm-6">
            <div class="float-right">
                @can('create-payments')
                    <a href="{{ route('payments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i> Record Payment
                    </a>
                @endcan
                <a href="{{ route('payments.outstanding') }}" class="btn btn-warning ml-1">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Outstanding Balances
                </a>
            </div>
        </div>
    </div>

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Payment History</h3>
        </div>
        <div class="card-body">
            <table id="dt-payments" class="table table-bordered table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Number</th>
                        <th>Type</th>
                        <th>Party</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Paid On</th>
                        <th>Actions</th>
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
