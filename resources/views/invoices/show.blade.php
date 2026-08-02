@extends('layouts.app')

@section('title', $invoice->number)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">{{ $invoice->number }} — {{ ucfirst(str_replace('_', ' ', $invoice->type)) }}</h3>
                <div class="card-tools">
                    <span class="badge {{ $invoice->status_badge }}">{{ ucfirst($invoice->status) }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Customer:</strong> {{ $invoice->customer?->company_name }}<br>
                        <strong>Date:</strong> {{ $invoice->invoice_date?->format('d M Y') }}<br>
                        <strong>Due:</strong> {{ $invoice->due_date?->format('d M Y') ?: '-' }}<br>
                        <strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $invoice->type)) }}
                    </div>
                    <div class="col-md-6 text-right">
                        <strong>Total:</strong> <span class="text-primary h4">{{ number_format($invoice->total, 2) }}</span><br>
                        <strong>Paid:</strong> <span class="text-success">{{ number_format($invoice->paid_amount, 2) }}</span><br>
                        <strong>Balance:</strong> <span class="{{ $invoice->balance > 0 ? 'text-danger' : 'text-success' }} h5">{{ number_format($invoice->balance, 2) }}</span>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead><tr><th>Description</th><th>Qty</th><th>Unit</th><th>Price</th><th>Tax %</th><th>Disc %</th><th>Line Total</th></tr></thead>
                    <tbody>
                    @foreach($invoice->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ $item->unit }}</td>
                            <td>{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $item->tax_rate }}%</td>
                            <td>{{ $item->discount_pct }}%</td>
                            <td>{{ number_format($item->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <table class="table table-sm">
                            <tr><td class="text-right"><strong>Subtotal</strong></td><td class="text-right">{{ number_format($invoice->subtotal, 2) }}</td></tr>
                            <tr><td class="text-right"><strong>Tax</strong></td><td class="text-right">{{ number_format($invoice->tax_amount, 2) }}</td></tr>
                            @if($invoice->discount > 0)
                                <tr><td class="text-right"><strong>Discount</strong></td><td class="text-right">-{{ number_format($invoice->discount, 2) }}</td></tr>
                            @endif
                            <tr class="table-primary"><td class="text-right"><strong>Total</strong></td><td class="text-right"><strong>{{ number_format($invoice->total, 2) }}</strong></td></tr>
                        </table>
                    </div>
                </div>

                @if($invoice->notes)
                    <div class="mt-3"><strong>Notes:</strong><br>{!! nl2br(e($invoice->notes)) !!}</div>
                @endif
                @if($invoice->terms)
                    <div class="mt-2"><strong>Terms:</strong><br>{!! nl2br(e($invoice->terms)) !!}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-danger btn-block"><i class="fas fa-file-pdf"></i> Download PDF</a>
                @if(in_array($invoice->status, ['draft']))
                    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-info btn-block"><i class="fas fa-edit"></i> Edit</a>
                @endif
                <a href="{{ route('invoices.index') }}" class="btn btn-default btn-block"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
        </div>

        @if($invoice->payments->count())
        <div class="card card-outline card-success">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-money-bill"></i> Payments</h3></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    @foreach($invoice->payments as $p)
                        <tr>
                            <td>{{ $p->paid_on?->format('d M Y') }}</td>
                            <td>{{ number_format($p->pivot->amount, 2) }}</td>
                            <td><small>{{ ucfirst($p->method) }}</small></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @endif

        @include('documents.partials._upload', ['morphType' => 'invoice', 'morphClass' => 'App\\Models\\Invoice', 'entity' => $invoice, 'documents' => $invoice->documents])
    </div>
</div>
@endsection
