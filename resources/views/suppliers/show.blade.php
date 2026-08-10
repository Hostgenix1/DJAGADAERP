@extends('layouts.app')

@section('title', $supplier->company_name)

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ $supplier->company_name }}</h1>
        </div>
        <div class="col-sm-6">
            <div class="float-right">
                @can('update-suppliers')
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-info"><i class="fas fa-pen mr-1"></i> Edit</a>
                @endcan
                <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-default"><i class="fas fa-arrow-left mr-1"></i> Back</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title">Supplier Details</h3></div>
                <div class="card-body">
                    <p class="mb-1"><strong>Contact Person:</strong> {{ $supplier->contact_person ?: '—' }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $supplier->email ?: '—' }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $supplier->phone ?: '—' }}</p>
                    <p class="mb-1"><strong>Address:</strong> {{ $supplier->address ?: '—' }}</p>
                    <p class="mb-1"><strong>City:</strong> {{ $supplier->city ?: '—' }}</p>
                    <p class="mb-1"><strong>Country:</strong> {{ $supplier->country ?: '—' }}</p>
                    <p class="mb-1"><strong>Currency:</strong> {{ $supplier->currency?->code ?: '—' }}</p>
                    <p class="mb-1"><strong>Payment Terms:</strong> {{ $supplier->payment_terms ?: '—' }}</p>
                    @if($supplier->tax_number)<p class="mb-1"><strong>Tax No:</strong> {{ $supplier->tax_number }}</p>@endif
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Purchase Orders</span>
                            <span class="info-box-number">{{ $supplier->purchaseOrders->where('status', '!=', 'cancelled')->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Purchases</span>
                            <span class="info-box-number">{{ $supplier->currency?->symbol ?? '' }}{{ number_format($totalBilled, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-hand-holding-usd"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Paid</span>
                            <span class="info-box-number">{{ $supplier->currency?->symbol ?? '' }}{{ number_format($totalPaid, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="info-box bg-danger">
                        <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Outstanding</span>
                            <span class="info-box-number">{{ $supplier->currency?->symbol ?? '' }}{{ number_format($outstanding, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-secondary">
                <div class="card-header"><h3 class="card-title">Purchase Orders</h3></div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead><tr><th>Number</th><th>Date</th><th>Status</th><th class="text-right">Total</th></tr></thead>
                        <tbody>
                        @forelse($supplier->purchaseOrders as $po)
                            <tr>
                                <td><a href="{{ route('purchase_orders.show', $po) }}">{{ $po->number }}</a></td>
                                <td>{{ $po->po_date?->format('d M Y') }}</td>
                                <td><span class="badge {{ $po->status_badge }}">{{ ucfirst($po->status) }}</span></td>
                                <td class="text-right">{{ $po->currency?->code ?? '' }} {{ number_format($po->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">No purchase orders.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">Supplier Bills & Payments</h3>
                    <div class="card-tools">
                        <span class="badge badge-danger">{{ $supplier->bills->where('status', '!=', 'cancelled')->whereRaw('total - paid_amount > 0')->count() }} open bills</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead><tr><th>Bill</th><th>Date</th><th>Status</th><th class="text-right">Total</th><th class="text-right">Paid</th><th class="text-right">Balance</th></tr></thead>
                        <tbody>
                        @forelse($supplier->bills as $bill)
                            <tr>
                                <td><a href="{{ route('supplier_bills.show', $bill) }}">{{ $bill->number }}</a></td>
                                <td>{{ $bill->bill_date?->format('d M Y') }}</td>
                                <td>{!! $bill->status_badge !!}</td>
                                <td class="text-right">{{ $bill->currency?->code ?? '' }} {{ number_format($bill->total, 2) }}</td>
                                <td class="text-right">{{ $bill->currency?->code ?? '' }} {{ number_format($bill->paid_amount, 2) }}</td>
                                <td class="text-right">{{ $bill->currency?->code ?? '' }} {{ number_format($bill->balance, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">No supplier bills yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card card-outline card-success">
                <div class="card-header"><h3 class="card-title">Payments Received</h3></div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0">
                        <thead><tr><th>Payment</th><th>Date</th><th>Method</th><th class="text-right">Amount</th><th>Reference</th></tr></thead>
                        <tbody>
                        @forelse($supplier->payments as $payment)
                            <tr>
                                <td><a href="{{ route('payments.show', $payment) }}">{{ $payment->number }}</a></td>
                                <td>{{ $payment->paid_on?->format('d M Y') }}</td>
                                <td>{{ ucfirst($payment->method) }}</td>
                                <td class="text-right">{{ $payment->currency?->code ?? '' }} {{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->reference ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">No payments recorded.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @include('documents.partials._upload', ['morphType' => 'supplier', 'morphClass' => 'App\\Models\\Supplier', 'entity' => $supplier, 'documents' => $supplier->documents])
        </div>
    </div>
</div>
@endsection
