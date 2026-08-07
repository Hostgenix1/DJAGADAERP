<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #2d3748; line-height: 1.5; width: 750px; margin: 0 auto; padding: 25px; }

.header { display: flex; justify-content: space-between; margin-bottom: 20px; }
.header-left { flex: 1; }
.header-right { text-align: right; flex: 1; }
.company-name { font-size: 18px; font-weight: bold; color: #1a365d; margin-bottom: 4px; }
.company-detail { font-size: 9px; color: #4a5568; }
.quote-title { font-size: 28px; font-weight: bold; color: #2b6cb0; text-transform: uppercase; margin-top: 10px; }
.quote-number { font-size: 12px; font-weight: bold; color: #2b6cb0; }

.info-bar { display: flex; justify-content: space-between; background: #edf2f7; padding: 10px 15px; border-radius: 4px; margin-bottom: 20px; }
.info-box { width: 48%; }
.info-box-title { font-size: 8px; font-weight: bold; text-transform: uppercase; color: #718096; letter-spacing: 0.5px; margin-bottom: 3px; }
.info-box-value { font-size: 10px; color: #2d3748; }

.items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
.items-table th { background: #2b6cb0; color: #fff; padding: 8px 10px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
.items-table th.right { text-align: right; }
.items-table td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
.items-table td.right { text-align: right; }
.items-table tr:nth-child(even) td { background: #f7fafc; }

.bottom-section { display: flex; justify-content: space-between; margin-top: 15px; }
.notes-box { width: 55%; font-size: 9px; color: #4a5568; }
.notes-box .label { font-weight: bold; color: #2d3748; margin-bottom: 3px; text-transform: uppercase; font-size: 8px; letter-spacing: 0.5px; }
.totals-box { width: 42%; }
.totals-table { width: 100%; border-collapse: collapse; }
.totals-table td { padding: 6px 10px; font-size: 10px; }
.totals-table .label-col { text-align: right; color: #4a5568; font-weight: 500; }
.totals-table .value-col { text-align: right; font-weight: 600; }
.totals-table .total-row td { border-top: 2px solid #2b6cb0; font-size: 14px; font-weight: bold; color: #2b6cb0; padding: 8px 10px; }

.bank-bar { background: #edf2f7; padding: 10px 15px; border-radius: 4px; margin-top: 20px; }
.bank-title { font-size: 8px; font-weight: bold; text-transform: uppercase; color: #718096; letter-spacing: 0.5px; margin-bottom: 5px; }
.bank-detail { font-size: 9px; color: #2d3748; display: inline-block; margin-right: 25px; }
.bank-detail strong { color: #1a365d; }

.footer { margin-top: 20px; text-align: center; font-size: 8px; color: #a0aec0; border-top: 1px solid #e2e8f0; padding-top: 10px; }
</style>
</head>
<body>

<div class="header">
    <div class="header-left">
        @if($company['show_logo'] && $company['logo_url'])
            <img src="{{ $company['logo_url'] }}" style="max-height:45px; margin-bottom:8px;">
        @endif
        <div class="company-name">{{ $company['name'] ?? '' }}</div>
        @if($company['address'])
            <div class="company-detail">{{ $company['address'] }}</div>
        @endif
        @if($company['city'] || $company['country'])
            <div class="company-detail">{{ $company['city'] }}{{ ($company['city'] && $company['country']) ? ', ' : '' }}{{ $company['country'] }}</div>
        @endif
        @if($company['email'])
            <div class="company-detail">Email: {{ $company['email'] }}</div>
        @endif
        @if($company['phone'])
            <div class="company-detail">Tel: {{ $company['phone'] }}</div>
        @endif
        @if($company['trn'])
            <div class="company-detail"><strong>TRN:</strong> {{ $company['trn'] }}</div>
        @endif
        @if($company['trade_license'])
            <div class="company-detail"><strong>Trade License:</strong> {{ $company['trade_license'] }}</div>
        @endif
        @if($company['free_zone'])
            <div class="company-detail"><strong>Free Zone:</strong> {{ $company['free_zone'] }}</div>
        @endif
    </div>
    <div class="header-right">
        <div class="quote-title">QUOTATION</div>
        <div class="quote-number">{{ $quote->number }}</div>
        @if($quote->revision > 1)
            <div class="company-detail" style="color:#e53e3e; font-weight:bold;">Revision {{ $quote->revision }}</div>
        @endif
        <div class="company-detail" style="margin-top:8px;">
            <strong>Date:</strong> {{ $quote->date?->format('d M Y') ?? '' }}
        </div>
        @if($quote->valid_until)
            <div class="company-detail">
                <strong>Valid Until:</strong> {{ $quote->valid_until->format('d M Y') }}
            </div>
        @endif
        <div class="company-detail">
            <strong>Status:</strong> {{ ucfirst($quote->status) }}
        </div>
        <div style="margin-top:8px; display:inline-block;">
            {!! $qrSvg !!}
        </div>
    </div>
</div>

<div class="info-bar">
    <div class="info-box">
        <div class="info-box-title">Quote For</div>
        <div style="font-weight:bold; font-size:11px; color:#1a365d;">{{ $quote->customer->company_name ?? 'N/A' }}</div>
        @if($quote->customer->contact_person)
            <div class="info-box-value">Attn: {{ $quote->customer->contact_person }}</div>
        @endif
        @if($quote->customer->address)
            <div class="info-box-value">{{ $quote->customer->address }}</div>
        @endif
        @if($quote->customer->city || $quote->customer->country)
            <div class="info-box-value">{{ $quote->customer->city }}{{ ($quote->customer->city && $quote->customer->country) ? ', ' : '' }}{{ $quote->customer->country }}</div>
        @endif
        @if($quote->customer->emirate)
            <div class="info-box-value">{{ $quote->customer->emirate }}</div>
        @endif
        @if($quote->customer->email)
            <div class="info-box-value">{{ $quote->customer->email }}</div>
        @endif
        @if($quote->customer->phone)
            <div class="info-box-value">Tel: {{ $quote->customer->phone }}</div>
        @endif
        @if($quote->customer->tax_registration_number)
            <div class="info-box-value"><strong>TRN:</strong> {{ $quote->customer->tax_registration_number }}</div>
        @endif
        @if($quote->customer->po_box)
            <div class="info-box-value">PO Box: {{ $quote->customer->po_box }}</div>
        @endif
    </div>
    <div class="info-box">
        <div class="info-box-title">Details</div>
        @if($quote->currency)
            <div class="info-box-value"><strong>Currency:</strong> {{ $quote->currency->name }} ({{ $quote->currency->code }})</div>
        @endif
    </div>
</div>

<table class="items-table">
    <thead>
        <tr>
            <th style="width:5%;">#</th>
            <th style="width:35%;">Description</th>
            <th style="width:8%;" class="right">Qty</th>
            <th style="width:8%;" class="right">Unit</th>
            <th style="width:12%;" class="right">Unit Price</th>
            <th style="width:8%;" class="right">Tax %</th>
            <th style="width:12%;" class="right">Discount</th>
            <th style="width:12%;" class="right">Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($quote->items as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>
                {{ $item->description ?? $item->product->name ?? '' }}
            </td>
            <td class="right">{{ number_format($item->qty, 2) }}</td>
            <td class="right">{{ $item->unit ?? '-' }}</td>
            <td class="right">{{ $quote->currency?->symbol ?? '$' }} {{ number_format($item->unit_price, 2) }}</td>
            <td class="right">{{ $item->tax_rate > 0 ? number_format($item->tax_rate, 1).'%' : '-' }}</td>
            <td class="right">{{ $item->discount_pct > 0 ? number_format($item->discount_pct, 2).'%' : '-' }}</td>
            <td class="right">{{ $quote->currency?->symbol ?? '$' }} {{ number_format($item->line_total, 2) }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="8" style="text-align:center; color:#a0aec0; padding:20px;">No items</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="bottom-section">
    <div class="notes-box">
        @if($quote->notes)
            <div class="label">Notes</div>
            <div>{{ $quote->notes }}</div>
        @endif
        @if($quote->terms)
            <div class="label" style="margin-top:8px;">Terms & Conditions</div>
            <div>{{ $quote->terms }}</div>
        @endif
    </div>

    <div class="totals-box">
        <table class="totals-table">
            <tr>
                <td class="label-col">Subtotal</td>
                <td class="value-col">{{ $quote->currency?->symbol ?? '$' }} {{ number_format($quote->subtotal, 2) }}</td>
            </tr>
            @if($quote->tax_amount > 0)
            <tr>
                <td class="label-col">Tax</td>
                <td class="value-col">{{ $quote->currency?->symbol ?? '$' }} {{ number_format($quote->tax_amount, 2) }}</td>
            </tr>
            @endif
            @if($quote->discount > 0)
            <tr>
                <td class="label-col">Discount</td>
                <td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? '$' }} {{ number_format($quote->discount, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td class="label-col">TOTAL</td>
                <td class="value-col">{{ $quote->currency?->symbol ?? '$' }} {{ number_format($quote->total, 2) }}</td>
            </tr>
        </table>
    </div>
</div>

@if($company['footer'])
<div class="footer">{{ $company['footer'] }}</div>
@endif

<div class="footer" style="margin-top:5px;">Generated by {{ $company['name'] ?? 'DJAGADA ERP' }}</div>

</body>
</html>
