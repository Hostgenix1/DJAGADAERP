@extends('layouts.app')

@section('title', 'Quotation '.$quote->number)

{{--
  Quotation Detail - Quotations Module
  Module: Quotations
  Features: Quote detail view, line items table, subtotal/tax/discount/total summary, status badge, revision tracking, convert to proforma/invoice, edit action, notes/terms display
  Version: 1.0.0
--}}

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">{{ $quote->number }} — {{ $quote->customer?->company_name }}</h3>
                <div class="card-tools">
                    <span class="badge {{ $quote->status_badge }} mr-2">{{ ucfirst($quote->status) }}</span>
                    <span class="text-muted">Rev {{ $quote->revision }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Customer:</strong> {{ $quote->customer?->company_name }}<br>
                        <strong>Date:</strong> {{ $quote->date?->format('d M Y') }}<br>
                        <strong>Valid Until:</strong> {{ $quote->valid_until?->format('d M Y') ?: '-' }}
                    </div>
                    <div class="col-md-6 text-right">
                        <strong>Currency:</strong> {{ $quote->currency?->code ?? 'Default' }}<br>
                        <strong>Revision:</strong> {{ $quote->revision }}
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead><tr><th>Description</th><th>Qty</th><th>Unit</th><th>Price</th><th>Tax %</th><th>Disc %</th><th>Line Total</th></tr></thead>
                    <tbody>
                    @foreach($quote->items as $item)
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
                            <tr><td class="text-right"><strong>Subtotal</strong></td><td class="text-right">{{ number_format($quote->subtotal, 2) }}</td></tr>
                            <tr><td class="text-right"><strong>Tax</strong></td><td class="text-right">{{ number_format($quote->tax_amount, 2) }}</td></tr>
                            @if($quote->discount > 0)
                                <tr><td class="text-right"><strong>Discount</strong></td><td class="text-right">-{{ number_format($quote->discount, 2) }}</td></tr>
                            @endif
                            <tr class="table-primary"><td class="text-right"><strong>Total</strong></td><td class="text-right"><strong>{{ number_format($quote->total, 2) }}</strong></td></tr>
                        </table>
                    </div>
                </div>

                @if($quote->notes)
                    <div class="mt-3"><strong>Notes:</strong><br>{!! nl2br(e($quote->notes)) !!}</div>
                @endif
                @if($quote->terms)
                    <div class="mt-2"><strong>Terms:</strong><br>{!! nl2br(e($quote->terms)) !!}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                @if(in_array($quote->status, ['draft', 'sent']))
                    <a href="{{ route('quotes.edit', $quote) }}" class="btn btn-info btn-block"><i class="fas fa-edit"></i> Edit</a>
                @endif
                @if($quote->status === 'draft')
                    <form method="POST" action="{{ route('quotes.convert', [$quote, 'proforma']) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-warning btn-block"><i class="fas fa-exchange-alt"></i> Convert to Proforma</button>
                    </form>
                @endif
                @if(in_array($quote->status, ['draft', 'sent', 'accepted']))
                    <form method="POST" action="{{ route('quotes.convert', [$quote, 'commercial']) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-success btn-block"><i class="fas fa-file-invoice"></i> Convert to Invoice</button>
                    </form>
                @endif
                <a href="{{ route('quotes.pdf', $quote) }}" class="btn btn-secondary btn-block"><i class="fas fa-file-pdf"></i> Download PDF</a>
                <a href="{{ route('quotes.index') }}" class="btn btn-default btn-block"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
        </div>
    </div>
</div>
@endsection
