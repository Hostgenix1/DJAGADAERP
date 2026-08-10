@extends('layouts.app')

@section('title', 'Supplier Bills')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row mb-3">
            <div class="col-sm-6">
                <h4 class="mb-0"><i class="fas fa-file-invoice-dollar mr-1"></i> Supplier Bills
                    @if($supplier)<span class="text-muted">— {{ $supplier->company_name }}</span>
                    <a href="{{ route('supplier_bills.index') }}" class="btn btn-sm btn-outline-secondary ml-2"><i class="fas fa-times mr-1"></i> Clear Filter</a>@endif
                </h4>
            </div>
            <div class="col-sm-6 text-right">
                @can('create-supplier-bills')
                    <a href="{{ route('supplier_bills.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> New Supplier Bill</a>
                @endcan
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box"><span class="info-box-icon bg-secondary"><i class="fas fa-file-invoice"></i></span>
                    <div class="info-box-content"><span class="info-box-text">Total Bills</span><span class="info-box-number">{{ $totalBills }}</span></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box"><span class="info-box-icon bg-info"><i class="fas fa-file-invoice-dollar"></i></span>
                    <div class="info-box-content"><span class="info-box-text">Total Amount</span><span class="info-box-number">{{ $defaultCurrency?->symbol ?? '$' }} {{ number_format($totalAmount, 2) }}</span></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box"><span class="info-box-icon bg-success"><i class="fas fa-hand-holding-usd"></i></span>
                    <div class="info-box-content"><span class="info-box-text">Paid</span><span class="info-box-number">{{ $defaultCurrency?->symbol ?? '$' }} {{ number_format($totalPaid, 2) }}</span></div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box"><span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
                    <div class="info-box-content"><span class="info-box-text">Outstanding (Payables)</span><span class="info-box-number">{{ $defaultCurrency?->symbol ?? '$' }} {{ number_format($totalOutstanding, 2) }}</span></div>
                </div>
            </div>
        </div>

        @if(!empty($billByCurrency))
        <div class="card card-outline card-info mb-3">
            <div class="card-header py-2"><h5 class="card-title mb-0"><i class="fas fa-coins mr-1"></i> Payables by Currency</h5></div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead><tr><th>Currency</th><th class="text-right">Count</th><th class="text-right">Total</th><th class="text-right">Paid</th><th class="text-right">Outstanding</th></tr></thead>
                    <tbody>
                    @foreach($billByCurrency as $row)
                        <tr>
                            <td><strong>{{ $row['code'] }}</strong> <span class="text-muted">{{ $row['symbol'] }}</span></td>
                            <td class="text-right">{{ $row['count'] }}</td>
                            <td class="text-right">{{ number_format($row['total'], 2) }}</td>
                            <td class="text-right">{{ number_format($row['paid'], 2) }}</td>
                            <td class="text-right text-danger">{{ number_format($row['total'] - $row['paid'], 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">All Supplier Bills</h3></div>
            <div class="card-body">
                <table id="dt-bills" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Number</th>
                            <th>Supplier</th>
                            <th>Bill Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Currency</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Balance</th>
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
    $('#dt-bills').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("supplier_bills.datatable") }}',
            data: function (d) { d.supplier_id = {{ $supplier ? $supplier->id : 'null' }}; }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'number', name: 'number' },
            { data: 'supplier.company_name', name: 'supplier.company_name' },
            { data: 'bill_date', name: 'bill_date' },
            { data: 'due_date', name: 'due_date' },
            { data: 'status', name: 'status' },
            { data: 'currency.code', name: 'currency.code' },
            { data: 'total', name: 'total', className: 'text-right' },
            { data: 'balance', name: 'balance', className: 'text-right' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']]
    });
});
</script>
@endpush
