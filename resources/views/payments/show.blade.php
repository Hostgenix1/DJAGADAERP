@extends('layouts.app')
@section('title', 'Payment '.$payment->number)

{{--
  Payment Detail - Payments Module
  Module: Payments
  Features: Payment detail card, allocated invoices list, document attachments, party info, amount/method/date/reference display, status badges
  Version: 1.0.0
--}}

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">Payment Details</h3></div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr><th width="30%">Number</th><td>{{ $payment->number }}</td></tr>
                    <tr><th>Type</th><td><span class="badge badge-{{ $payment->type==='customer'?'success':'warning' }}">{{ ucfirst($payment->type) }}</span></td></tr>
                    <tr><th>Party</th><td>{{ $payment->customer?->company_name ?? $payment->supplier?->company_name ?? '-' }}</td></tr>
                    <tr><th>Amount</th><td><strong>{{ number_format($payment->amount, 2) }}</strong></td></tr>
                    <tr><th>Method</th><td>{{ ucfirst($payment->method) }}</td></tr>
                    <tr><th>Date</th><td>{{ $payment->paid_on?->format('d M Y') }}</td></tr>
                    <tr><th>Reference</th><td>{{ $payment->reference ?? '-' }}</td></tr>
                    <tr><th>Notes</th><td>{{ $payment->notes ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        @if($payment->invoices->count())
        <div class="card card-success card-outline">
            <div class="card-header"><h3 class="card-title">Allocated Invoices</h3></div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead><tr><th>Invoice</th><th>Customer</th><th>Allocated</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach($payment->invoices as $inv)
                    <tr>
                        <td><a href="{{ route('invoices.show', $inv->id) }}">{{ $inv->number }}</a></td>
                        <td>{{ $inv->customer?->company_name }}</td>
                        <td>{{ number_format($inv->pivot->amount, 2) }}</td>
                        <td>{!! $inv->status_badge !!}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if($payment->supplierBills->count())
        <div class="card card-warning card-outline">
            <div class="card-header"><h3 class="card-title">Allocated Supplier Bills</h3></div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead><tr><th>Bill</th><th>Supplier</th><th>Allocated</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach($payment->supplierBills as $bill)
                    <tr>
                        <td><a href="{{ route('supplier_bills.show', $bill->id) }}">{{ $bill->number }}</a></td>
                        <td>{{ $bill->supplier?->company_name }}</td>
                        <td>{{ number_format($bill->pivot->amount, 2) }}</td>
                        <td>{!! $bill->status_badge !!}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @include('documents.partials._upload', ['morphType' => 'payment', 'morphClass' => 'App\\Models\\Payment', 'entity' => $payment, 'documents' => $payment->documents])
    </div>
</div>
@endsection
