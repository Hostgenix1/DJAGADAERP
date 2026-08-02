@extends('layouts.app')

@section('title', $invoice->number)

{{--
  Invoice Detail - Invoices Module
  Module: Invoices
  Features: Invoice detail view, line items table, subtotal/tax/discount/total summary, payment history, document upload, PDF download, status badge, paid/balance tracking, notes/terms
  Version: 1.0.0
--}}

@section('content')

{{-- Top action bar --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Invoices
        </a>
    </div>
    <div class="d-flex">
        <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-danger btn-sm mr-2">
            <i class="fas fa-file-pdf mr-1"></i> Download PDF
        </a>
        @if(in_array($invoice->status, ['draft']))
            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-info btn-sm mr-2">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
        @endif
        @if(in_array($invoice->status, ['draft', 'cancelled']))
            <form method="POST" action="{{ route('invoices.destroy', $invoice) }}" class="d-inline" onsubmit="return confirm('Delete this invoice?');">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash mr-1"></i> Delete</button>
            </form>
        @endif
    </div>
</div>

<div class="row">
    {{-- Main content --}}
    <div class="col-lg-8">

        {{-- Invoice header card --}}
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @php
                            $statusClass = match($invoice->status) {
                                'paid' => 'success',
                                'sent' => 'info',
                                'partial' => 'warning',
                                'overdue' => 'danger',
                                'cancelled' => 'dark',
                                default => 'secondary'
                            };
                        @endphp
                        <h4 class="mb-1" style="color: #1e3a5f;">{{ $invoice->number }}</h4>
                        <span class="badge badge-{{ $statusClass }} badge-pill px-3 py-1 mb-3">{{ strtoupper($invoice->status) }}</span>
                        <p class="text-muted mb-0">{{ ucfirst(str_replace('_', ' ', $invoice->type)) }}</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="mb-2">
                            <small class="text-muted">INVOICE DATE</small><br>
                            <strong>{{ $invoice->invoice_date?->format('d M Y') ?: '-' }}</strong>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">DUE DATE</small><br>
                            <strong class="{{ $invoice->due_date && $invoice->due_date->isPast() && $invoice->balance > 0 ? 'text-danger' : '' }}">
                                {{ $invoice->due_date?->format('d M Y') ?: '-' }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Billing info --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="card card-outline card-light" style="border-left: 4px solid #1e3a5f;">
                    <div class="card-body py-2">
                        <small class="text-muted text-uppercase font-weight-bold">Bill To</small>
                        <div class="mt-1">
                            <strong class="text-dark">{{ $invoice->customer?->company_name ?: '-' }}</strong>
                            @if($invoice->customer?->contact_person)<br><small class="text-muted">{{ $invoice->customer->contact_person }}</small>@endif
                            @if($invoice->customer?->address)<br><small class="text-muted">{{ $invoice->customer->address }}</small>@endif
                            @if($invoice->customer?->city || $invoice->customer?->country)<br><small class="text-muted">{{ $invoice->customer->city }}{{ ($invoice->customer->city && $invoice->customer->country) ? ', ' : '' }}{{ $invoice->customer->country }}</small>@endif
                            @if($invoice->customer?->email)<br><small class="text-muted"><i class="fas fa-envelope fa-xs mr-1"></i>{{ $invoice->customer->email }}</small>@endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                @php $svc = app(\App\Services\SettingsService::class); @endphp
                <div class="card card-outline card-light" style="border-left: 4px solid #6c757d;">
                    <div class="card-body py-2">
                        <small class="text-muted text-uppercase font-weight-bold">From</small>
                        <div class="mt-1">
                            <strong class="text-dark">{{ $svc->get('company_name') ?: 'Your Company' }}</strong>
                            @if($svc->get('company_address'))<br><small class="text-muted">{{ $svc->get('company_address') }}</small>@endif
                            @if($svc->get('company_city') || $svc->get('company_country'))<br><small class="text-muted">{{ $svc->get('company_city') }}{{ ($svc->get('company_city') && $svc->get('company_country')) ? ', ' : '' }}{{ $svc->get('company_country') }}</small>@endif
                            @if($svc->get('company_email'))<br><small class="text-muted"><i class="fas fa-envelope fa-xs mr-1"></i>{{ $svc->get('company_email') }}</small>@endif
                            @if($svc->get('company_tax_id'))<br><small class="text-muted"><i class="fas fa-id-card fa-xs mr-1"></i>Tax ID: {{ $svc->get('company_tax_id') }}</small>@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Line items --}}
        <div class="card card-outline shadow-sm">
            <div class="card-header" style="background: #1e3a5f; color: #fff; border-radius: 0;">
                <h5 class="card-title mb-0"><i class="fas fa-list mr-2"></i>Line Items</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th style="width:5%; border-top:none;">#</th>
                            <th style="width:30%; border-top:none;">DESCRIPTION</th>
                            <th style="width:8%; border-top:none; text-align:center;">QTY</th>
                            <th style="width:8%; border-top:none; text-align:center;">UNIT</th>
                            <th style="width:14%; border-top:none; text-align:right;">PRICE</th>
                            <th style="width:10%; border-top:none; text-align:center;">TAX</th>
                            <th style="width:12%; border-top:none; text-align:center;">DISC</th>
                            <th style="width:15%; border-top:none; text-align:right;">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoice->items as $i => $item)
                        <tr>
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td><strong>{{ $item->description }}</strong></td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td class="text-center"><small class="text-muted">{{ $item->unit }}</small></td>
                            <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-center">
                                @if($item->tax_rate > 0)
                                    <span class="badge badge-light">{{ $item->tax_rate }}%</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->discount_pct > 0)
                                    <span class="badge badge-light text-danger">{{ $item->discount_pct }}%</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-right font-weight-bold" style="color: #1e3a5f;">{{ number_format($item->line_total, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>No items</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Totals --}}
        <div class="row mb-4">
            <div class="col-md-7">
                {{-- Notes & Terms --}}
                @if($invoice->notes || $invoice->terms)
                <div class="card card-outline card-light">
                    <div class="card-body">
                        @if($invoice->notes)
                            <div class="mb-2">
                                <small class="text-muted text-uppercase font-weight-bold">Notes</small>
                                <div class="mt-1 text-dark" style="font-size: 13px;">{!! nl2br(e($invoice->notes)) !!}</div>
                            </div>
                        @endif
                        @if($invoice->terms)
                            <div>
                                <small class="text-muted text-uppercase font-weight-bold">Terms &amp; Conditions</small>
                                <div class="mt-1 text-dark" style="font-size: 13px;">{!! nl2br(e($invoice->terms)) !!}</div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            <div class="col-md-5">
                <div class="card card-outline shadow-sm">
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="text-right text-muted" style="width:50%;">Subtotal</td>
                                <td class="text-right font-weight-bold" style="width:50%;">{{ $invoice->currency?->symbol ?? '$' }} {{ number_format($invoice->subtotal, 2) }}</td>
                            </tr>
                            @if($invoice->tax_amount > 0)
                            <tr>
                                <td class="text-right text-muted">Tax</td>
                                <td class="text-right">{{ $invoice->currency?->symbol ?? '$' }} {{ number_format($invoice->tax_amount, 2) }}</td>
                            </tr>
                            @endif
                            @if($invoice->discount > 0)
                            <tr>
                                <td class="text-right text-muted">Discount</td>
                                <td class="text-right text-danger">-{{ $invoice->currency?->symbol ?? '$' }} {{ number_format($invoice->discount, 2) }}</td>
                            </tr>
                            @endif
                            <tr style="background: #1e3a5f;">
                                <td class="text-right text-white font-weight-bold" style="border:none;">TOTAL DUE</td>
                                <td class="text-right text-white font-weight-bold" style="border:none; font-size: 16px;">{{ $invoice->currency?->symbol ?? '$' }} {{ number_format($invoice->total, 2) }}</td>
                            </tr>
                            @if($invoice->paid_amount > 0)
                            <tr>
                                <td class="text-right font-weight-bold" style="color: #166534;">Paid</td>
                                <td class="text-right font-weight-bold" style="color: #166534;">-{{ $invoice->currency?->symbol ?? '$' }} {{ number_format($invoice->paid_amount, 2) }}</td>
                            </tr>
                            @endif
                            @if($invoice->balance > 0)
                            <tr style="background: #fef2f2;">
                                <td class="text-right font-weight-bold text-danger" style="border:none;">BALANCE DUE</td>
                                <td class="text-right font-weight-bold text-danger" style="border:none; font-size: 15px;">{{ $invoice->currency?->symbol ?? '$' }} {{ number_format($invoice->balance, 2) }}</td>
                            </tr>
                            @elseif($invoice->paid_amount > 0)
                            <tr style="background: #f0fdf4;">
                                <td class="text-right font-weight-bold text-success" style="border:none;">BALANCE</td>
                                <td class="text-right font-weight-bold text-success" style="border:none; font-size: 14px;">
                                    <i class="fas fa-check-circle mr-1"></i>PAID IN FULL
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">

        {{-- Payments --}}
        <div class="card card-outline shadow-sm {{ $invoice->payments->count() ? 'card-success' : 'card-light' }}">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-money-bill-wave mr-2"></i>Payments ({{ $invoice->payments->count() }})</h5>
            </div>
            @if($invoice->payments->count())
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th>Date</th>
                            <th class="text-right">Amount</th>
                            <th>Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->payments as $p)
                        <tr>
                            <td><small>{{ $p->paid_on?->format('d M Y') }}</small></td>
                            <td class="text-right font-weight-bold" style="color: #166534;">{{ number_format($p->pivot->amount, 2) }}</td>
                            <td><span class="badge badge-light">{{ ucfirst($p->method) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="card-body text-center text-muted py-3">
                <i class="fas fa-receipt fa-2x mb-2 d-block"></i>
                <small>No payments recorded yet</small>
            </div>
            @endif
        </div>

        {{-- Documents --}}
        @include('documents.partials._upload', ['morphType' => 'invoice', 'morphClass' => 'App\\Models\\Invoice', 'entity' => $invoice, 'documents' => $invoice->documents])

        {{-- Quick Info --}}
        <div class="card card-outline card-light shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-info-circle mr-2"></i>Invoice Info</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <small class="text-muted">Invoice #</small>
                        <small class="font-weight-bold">{{ $invoice->number }}</small>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <small class="text-muted">Type</small>
                        <small class="font-weight-bold">{{ ucfirst(str_replace('_', ' ', $invoice->type)) }}</small>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <small class="text-muted">Status</small>
                        <span class="badge badge-{{ $statusClass }}">{{ ucfirst($invoice->status) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <small class="text-muted">Currency</small>
                        <small class="font-weight-bold">{{ $invoice->currency?->code ?? 'USD' }}</small>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <small class="text-muted">Created</small>
                        <small>{{ $invoice->created_at?->format('d M Y H:i') }}</small>
                    </li>
                    @if($invoice->quote)
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <small class="text-muted">From Quote</small>
                        <a href="{{ route('quotes.show', $invoice->quote) }}"><small>{{ $invoice->quote->number }}</small></a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
