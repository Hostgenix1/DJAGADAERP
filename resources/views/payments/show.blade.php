@extends('layouts.app')
@section('title', 'Payment '.$payment->number)
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

        @if($payment->documents->count())
        <div class="card card-info card-outline">
            <div class="card-header"><h3 class="card-title">Attachments</h3></div>
            <div class="card-body">
                <ul class="list-unstyled">
                @foreach($payment->documents as $doc)
                    <li><a href="{{ route('documents.download', $doc->id) }}"><i class="fas fa-file"></i> {{ $doc->name }}</a> <small class="text-muted">{{ $doc->formatted_size }}</small></li>
                @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
