{{--
  Invoice PDF Template - Invoices Module
  Module: Invoices
  Features: Modern PDF invoice design, company logo/branding, billing addresses, line items table, subtotal/tax/discount/total, payment details (bank info), QR code, notes/terms, footer, status pill
  Version: 1.0.0
--}}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
    * { margin: 0; padding: 0; }
    body { font-family: Helvetica, Arial, sans-serif; font-size: 10px; color: #2d3748; line-height: 1.5; width: 750px; margin: 0 auto; padding: 0 25px; }

    .top-bar { background: #1e3a5f; height: 6px; width: 100%; }

    .header-table { width: 100%; margin: 15px 0 10px 0; border-collapse: collapse; }
    .header-table td { vertical-align: top; }
    .company-info { width: 55%; padding-right: 15px; }
    .company-name { font-size: 18px; font-weight: bold; color: #1e3a5f; margin-bottom: 4px; }
    .company-detail { font-size: 9px; color: #64748b; line-height: 1.6; }
    .invoice-title-box { width: 45%; text-align: right; }
    .invoice-title { font-size: 28px; font-weight: bold; color: #1e3a5f; letter-spacing: 2px; margin-bottom: 4px; }
    .invoice-number { font-size: 13px; color: #475569; margin-bottom: 6px; }

    .status-pill { padding: 3px 12px; font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #ffffff; }
    .status-paid { background: #166534; }
    .status-draft { background: #64748b; }
    .status-sent { background: #1e40af; }
    .status-partial { background: #92400e; }
    .status-overdue { background: #991b1b; }
    .status-cancelled { background: #1e293b; }

    .meta-table { width: 100%; margin: 8px 0 12px 0; border-collapse: collapse; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; }
    .meta-table td { padding: 6px 0; font-size: 9px; color: #475569; }
    .meta-table .label { font-weight: bold; color: #334155; width: 70px; }

    .billing-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
    .billing-table td { width: 50%; vertical-align: top; padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; }
    .billing-label { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 6px; }
    .billing-name { font-size: 12px; font-weight: bold; color: #1e3a5f; margin-bottom: 3px; }
    .billing-detail { font-size: 9px; color: #475569; line-height: 1.7; }

    .items-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    .items-table th { background: #1e3a5f; color: #ffffff; padding: 8px 6px; font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
    .items-table td { padding: 7px 6px; border-bottom: 1px solid #e2e8f0; font-size: 9.5px; }
    .items-table tr:nth-child(even) td { background: #f8fafc; }
    .items-table tr:nth-child(odd) td { background: #ffffff; }
    .col-num { width: 5%; text-align: center; color: #94a3b8; }
    .col-desc { width: 30%; }
    .col-qty { width: 8%; text-align: center; }
    .col-unit { width: 8%; text-align: center; color: #64748b; }
    .col-price { width: 14%; text-align: right; }
    .col-tax { width: 10%; text-align: center; color: #64748b; }
    .col-total { width: 15%; text-align: right; font-weight: bold; }

    .bottom-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
    .bottom-table td { vertical-align: top; }

    .qr-box { width: 35%; padding: 10px 10px 10px 0; }
    .qr-inner { border: 1px solid #e2e8f0; padding: 10px; text-align: center; width: 130px; }
    .qr-inner img { width: 110px; height: 110px; }
    .qr-label { font-size: 7px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 4px; }

    .totals-box { width: 65%; }
    .totals-table { width: 260px; border-collapse: collapse; margin-left: auto; }
    .totals-table td { padding: 4px 10px; font-size: 9.5px; }
    .totals-table .label-col { text-align: left; color: #64748b; }
    .totals-table .value-col { text-align: right; font-weight: bold; }
    .totals-table .sep td { border-bottom: 1px solid #e2e8f0; }
    .totals-table .total-row td { background: #1e3a5f; color: #ffffff; font-size: 12px; font-weight: bold; padding: 8px 10px; }
    .totals-table .paid-row td { color: #166534; font-weight: bold; }
    .totals-table .balance-row td { color: #991b1b; font-weight: bold; font-size: 11px; }
    .totals-table .balance-ok td { color: #166534; font-weight: bold; }

    .payment-strip { background: #f1f5f9; padding: 8px 14px; margin-bottom: 12px; }
    .payment-strip-title { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-bottom: 4px; }
    .payment-strip-detail { font-size: 9px; color: #475569; }

    .notes-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    .notes-table td { width: 50%; vertical-align: top; padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; }
    .notes-title { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-bottom: 4px; }
    .notes-content { font-size: 9px; color: #475569; line-height: 1.6; }

    .footer-line { border-top: 1px solid #e2e8f0; padding-top: 10px; margin-top: 20px; text-align: center; font-size: 8px; color: #94a3b8; }
    .footer-thank { font-size: 11px; font-weight: bold; color: #1e3a5f; margin-bottom: 3px; }
</style>
</head>
<body>

<div class="top-bar"></div>

<table class="header-table">
<tr>
    <td class="company-info">
        @if(!empty($company['show_logo']) && !empty($company['logo_url']))
            <img src="{{ $company['logo_url'] }}" style="height: 40px; margin-bottom: 6px;">
        @endif
        <div class="company-name">{{ $company['name'] ?? 'Your Company' }}</div>
        <div class="company-detail">
            @if($company['address']) {{ $company['address'] }}<br>@endif
            @if($company['city']) {{ $company['city'] }}@if($company['country']), {{ $company['country'] }}@endif<br>@endif
            @if($company['email']) {{ $company['email'] }}<br>@endif
            @if($company['phone']) {{ $company['phone'] }}<br>@endif
            @if($company['tax_id']) VAT/Tax ID: {{ $company['tax_id'] }}@endif
        </div>
    </td>
    <td class="invoice-title-box">
        <div class="invoice-title">{{ strtoupper(str_replace('_', ' ', $invoice->type)) }}</div>
        <div class="invoice-number">{{ $invoice->number }}</div>
        @php
            $statusClass = match($invoice->status) {
                'paid' => 'status-paid',
                'sent' => 'status-sent',
                'partial' => 'status-partial',
                'overdue' => 'status-overdue',
                'cancelled' => 'status-cancelled',
                default => 'status-draft'
            };
        @endphp
        <span class="status-pill {{ $statusClass }}">{{ strtoupper($invoice->status) }}</span>
    </td>
</tr>
</table>

<table class="meta-table">
<tr>
    <td class="label">Date</td>
    <td>{{ $invoice->invoice_date?->format('d M Y') ?: '-' }}</td>
    <td class="label">Due Date</td>
    <td>{{ $invoice->due_date?->format('d M Y') ?: '-' }}</td>
    <td class="label">Currency</td>
    <td>{{ $invoice->currency?->code ?? 'USD' }}</td>
</tr>
</table>

<table class="billing-table">
<tr>
    <td>
        <div class="billing-label">Billed To</div>
        <div class="billing-name">{{ $invoice->customer?->company_name ?: '-' }}</div>
        <div class="billing-detail">
            @if($invoice->customer?->contact_person) {{ $invoice->customer->contact_person }}<br>@endif
            @if($invoice->customer?->address) {{ $invoice->customer->address }}<br>@endif
            @if($invoice->customer?->city || $invoice->customer?->country)
                {{ $invoice->customer->city }}{{ ($invoice->customer->city && $invoice->customer->country) ? ', ' : '' }}{{ $invoice->customer->country }}<br>
            @endif
            @if($invoice->customer?->email) {{ $invoice->customer->email }}<br>@endif
            @if($invoice->customer?->phone) {{ $invoice->customer->phone }}@endif
        </div>
    </td>
    <td>
        <div class="billing-label">From</div>
        <div class="billing-name">{{ $company['name'] ?? '-' }}</div>
        <div class="billing-detail">
            @if($company['address']) {{ $company['address'] }}<br>@endif
            @if($company['city']) {{ $company['city'] }}{{ ($company['city'] && $company['country']) ? ', ' : '' }}{{ $company['country'] }}<br>@endif
            @if($company['email']) {{ $company['email'] }}<br>@endif
            @if($company['phone']) {{ $company['phone'] }}<br>@endif
            @if($company['tax_id']) Tax ID: {{ $company['tax_id'] }}@endif
        </div>
    </td>
</tr>
</table>

<table class="items-table">
<thead>
<tr>
    <th class="col-num">#</th>
    <th class="col-desc">DESCRIPTION</th>
    <th class="col-qty">QTY</th>
    <th class="col-unit">UNIT</th>
    <th class="col-price">PRICE</th>
    <th class="col-tax">TAX</th>
    <th class="col-total">TOTAL</th>
</tr>
</thead>
<tbody>
@forelse($invoice->items as $i => $item)
<tr>
    <td class="col-num">{{ $i + 1 }}</td>
    <td class="col-desc">{{ $item->description }}</td>
    <td class="col-qty">{{ $item->qty }}</td>
    <td class="col-unit">{{ $item->unit }}</td>
    <td class="col-price">{{ $invoice->currency?->symbol ?? '$' }} {{ number_format($item->unit_price, 2) }}</td>
    <td class="col-tax">{{ $item->tax_rate > 0 ? $item->tax_rate.'%' : '-' }}</td>
    <td class="col-total">{{ $invoice->currency?->symbol ?? '$' }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="7" style="text-align:center; color:#94a3b8; padding:15px;">No items</td></tr>
@endforelse
</tbody>
</table>

<table class="bottom-table">
<tr>
    <td class="qr-box">
        @if(!empty($qrSvg))
        <div class="qr-inner">
            {!! $qrSvg !!}
            <div class="qr-label">Scan to verify</div>
        </div>
        @endif
    </td>
    <td class="totals-box">
        <table class="totals-table">
            <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $invoice->currency?->symbol ?? '$' }} {{ number_format($invoice->subtotal, 2) }}</td></tr>
            @if($invoice->tax_amount > 0)
                <tr class="sep"><td class="label-col">Tax</td><td class="value-col">{{ $invoice->currency?->symbol ?? '$' }} {{ number_format($invoice->tax_amount, 2) }}</td></tr>
            @endif
            @if($invoice->discount > 0)
                <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $invoice->currency?->symbol ?? '$' }} {{ number_format($invoice->discount, 2) }}</td></tr>
            @endif
            <tr class="total-row">
                <td class="label-col" style="color:#ffffff;">TOTAL DUE</td>
                <td class="value-col" style="color:#ffffff;">{{ $invoice->currency?->symbol ?? '$' }} {{ number_format($invoice->total, 2) }}</td>
            </tr>
            @if($invoice->paid_amount > 0)
                <tr class="paid-row">
                    <td class="label-col">PAID</td>
                    <td class="value-col">-{{ $invoice->currency?->symbol ?? '$' }} {{ number_format($invoice->paid_amount, 2) }}</td>
                </tr>
            @endif
            @if($invoice->balance > 0)
                <tr class="balance-row">
                    <td class="label-col">BALANCE DUE</td>
                    <td class="value-col">{{ $invoice->currency?->symbol ?? '$' }} {{ number_format($invoice->balance, 2) }}</td>
                </tr>
            @elseif($invoice->paid_amount > 0)
                <tr class="balance-ok">
                    <td class="label-col">BALANCE</td>
                    <td class="value-col">PAID IN FULL</td>
                </tr>
            @endif
        </table>
    </td>
</tr>
</table>

@if($company['bank_name'] || $company['bank_account'] || $company['bank_iban'] || $company['bank_swift'])
<div class="payment-strip">
    <div class="payment-strip-title">Payment Details</div>
    <div class="payment-strip-detail">
        @if($company['bank_name']) Bank: {{ $company['bank_name'] }} &nbsp;&nbsp; @endif
        @if($company['bank_account']) Account: {{ $company['bank_account'] }} &nbsp;&nbsp; @endif
        @if($company['bank_iban']) IBAN: {{ $company['bank_iban'] }} &nbsp;&nbsp; @endif
        @if($company['bank_swift']) SWIFT: {{ $company['bank_swift'] }}@endif
    </div>
</div>
@endif

@if($invoice->notes || $invoice->terms)
<table class="notes-table">
<tr>
@if($invoice->notes)
    <td>
        <div class="notes-title">Notes</div>
        <div class="notes-content">{{ $invoice->notes }}</div>
    </td>
@endif
@if($invoice->terms)
    <td>
        <div class="notes-title">Terms &amp; Conditions</div>
        <div class="notes-content">{{ $invoice->terms }}</div>
    </td>
@endif
</tr>
</table>
@endif

<div class="footer-line">
    <div class="footer-thank">Thank you for your business!</div>
    @if($company['footer']) {{ $company['footer'] }}<br>@endif
    {{ $company['name'] ?? '' }} &bull; Generated by Djagada ERP
</div>

</body>
</html>
