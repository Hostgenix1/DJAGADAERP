<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Helvetica, Arial, sans-serif; font-size: 10px; color: #2d3748; line-height: 1.5; width: 750px; margin: 0 auto; padding: 25px; }

    .top-bar { background: #1e3a5f; height: 6px; width: 100%; margin-bottom: 18px; }

    .header-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    .header-table td { vertical-align: top; }
    .company-info { width: 60%; padding-right: 15px; }
    .company-logo { height: 50px; margin-bottom: 8px; }
    .company-name { font-size: 20px; font-weight: bold; color: #1e3a5f; margin-bottom: 5px; }
    .company-detail { font-size: 9px; color: #64748b; line-height: 1.7; }
    .invoice-title-box { width: 40%; text-align: right; }
    .invoice-title { font-size: 30px; font-weight: bold; color: #1e3a5f; letter-spacing: 2px; margin-bottom: 5px; }
    .invoice-number { font-size: 14px; color: #475569; margin-bottom: 8px; }

    .status-pill { padding: 4px 14px; font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #ffffff; border-radius: 3px; }
    .status-paid { background: #166534; }
    .status-draft { background: #64748b; }
    .status-sent { background: #1e40af; }
    .status-partial { background: #92400e; }
    .status-overdue { background: #991b1b; }
    .status-cancelled { background: #1e293b; }

    .meta-table { width: 100%; margin: 10px 0 15px 0; border-collapse: collapse; border-top: 2px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; }
    .meta-table td { padding: 7px 0; font-size: 9.5px; color: #475569; }
    .meta-table .label { font-weight: bold; color: #334155; width: 75px; }

    .billing-table { width: 100%; margin-bottom: 18px; border-collapse: collapse; }
    .billing-table td { width: 50%; vertical-align: top; padding: 14px; background: #f8fafc; border: 1px solid #e2e8f0; }
    .billing-label { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.2px; color: #94a3b8; margin-bottom: 7px; }
    .billing-name { font-size: 13px; font-weight: bold; color: #1e3a5f; margin-bottom: 4px; }
    .billing-detail { font-size: 9.5px; color: #475569; line-height: 1.7; }

    .items-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
    .items-table th { background: #1e3a5f; color: #ffffff; padding: 9px 7px; font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
    .items-table td { padding: 8px 7px; border-bottom: 1px solid #e2e8f0; font-size: 9.5px; }
    .items-table tr:nth-child(even) td { background: #f8fafc; }
    .items-table tr:nth-child(odd) td { background: #ffffff; }
    .col-num { width: 4%; text-align: center; color: #94a3b8; }
    .col-desc { width: 32%; }
    .col-qty { width: 7%; text-align: center; }
    .col-unit { width: 7%; text-align: center; color: #64748b; }
    .col-price { width: 15%; text-align: right; }
    .col-tax { width: 10%; text-align: center; color: #64748b; }
    .col-total { width: 15%; text-align: right; font-weight: bold; }

    .bottom-table { width: 100%; margin-bottom: 18px; border-collapse: collapse; }
    .bottom-table td { vertical-align: top; }

    .qr-box { width: 30%; padding: 10px 10px 10px 0; }
    .qr-inner { border: 1px solid #e2e8f0; padding: 12px; text-align: center; width: 130px; background: #ffffff; }
    .qr-inner svg { width: 100px; height: 100px; }
    .qr-label { font-size: 7px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 5px; }

    .totals-box { width: 70%; }
    .totals-table { width: 280px; border-collapse: collapse; margin-left: auto; }
    .totals-table td { padding: 5px 12px; font-size: 9.5px; }
    .totals-table .label-col { text-align: left; color: #64748b; }
    .totals-table .value-col { text-align: right; font-weight: bold; }
    .totals-table .sep td { border-bottom: 1px solid #e2e8f0; }
    .totals-table .total-row td { background: #1e3a5f; color: #ffffff; font-size: 12px; font-weight: bold; padding: 9px 12px; }
    .totals-table .paid-row td { color: #166534; font-weight: bold; }
    .totals-table .balance-row td { color: #991b1b; font-weight: bold; font-size: 11px; }
    .totals-table .balance-ok td { color: #166534; font-weight: bold; }

    .payment-strip { background: #f1f5f9; padding: 10px 16px; margin-bottom: 15px; border-left: 4px solid #1e3a5f; }
    .payment-strip-title { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-bottom: 5px; }
    .payment-strip-detail { font-size: 9.5px; color: #475569; }
    .payment-strip-detail span { margin-right: 18px; }

    .notes-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    .notes-table td { width: 50%; vertical-align: top; padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; }
    .notes-title { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-bottom: 5px; }
    .notes-content { font-size: 9.5px; color: #475569; line-height: 1.7; }

    .signature-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    .signature-table td { width: 50%; vertical-align: top; padding: 15px 20px; }
    .sig-label { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-bottom: 5px; }
    .sig-line { border-bottom: 1px solid #cbd5e1; height: 40px; margin-bottom: 5px; }
    .sig-hint { font-size: 8px; color: #cbd5e1; }

    .stamp-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    .stamp-table td { padding: 15px 20px; text-align: center; }
    .stamp-area { border: 2px dashed #cbd5e1; border-radius: 8px; padding: 20px; width: 200px; height: 100px; margin: 0 auto; display: flex; align-items: center; justify-content: center; }
    .stamp-text { font-size: 9px; color: #cbd5e1; text-transform: uppercase; letter-spacing: 1px; }

    .footer-line { border-top: 2px solid #e2e8f0; padding-top: 12px; margin-top: 25px; text-align: center; }
    .footer-company { font-size: 11px; font-weight: bold; color: #1e3a5f; margin-bottom: 3px; }
    .footer-text { font-size: 8px; color: #94a3b8; }
</style>
</head>
<body>

<div class="top-bar"></div>

<table class="header-table">
<tr>
    <td class="company-info">
        @if(!empty($company['show_logo']) && !empty($company['logo_url']))
            <img src="{{ $company['logo_url'] }}" class="company-logo">
        @endif
        <div class="company-name">{{ $company['name'] ?? 'Your Company' }}</div>
        <div class="company-detail">
            @if($company['address']) {{ $company['address'] }}<br>@endif
            @if($company['city']) {{ $company['city'] }}@if($company['country']), {{ $company['country'] }}@endif<br>@endif
            @if($company['phone']) Phone: {{ $company['phone'] }}<br>@endif
            @if($company['email']) Email: {{ $company['email'] }}<br>@endif
            @if(!empty($company['registration'])) Reg: {{ $company['registration'] }}<br>@endif
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
        <div class="billing-label">Bill To</div>
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
<tr><td colspan="7" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
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

@if(!empty($company['bank_name']) || !empty($company['bank_account']) || !empty($company['bank_iban']) || !empty($company['bank_swift']))
<div class="payment-strip">
    <div class="payment-strip-title">Payment Details</div>
    <div class="payment-strip-detail">
        @if(!empty($company['bank_name']))<span>Bank: {{ $company['bank_name'] }}</span>@endif
        @if(!empty($company['bank_account']))<span>Account: {{ $company['bank_account'] }}</span>@endif
        @if(!empty($company['bank_number']))<span>Account No: {{ $company['bank_number'] }}</span>@endif
        @if(!empty($company['bank_iban']))<span>IBAN: {{ $company['bank_iban'] }}</span>@endif
        @if(!empty($company['bank_swift']))<span>SWIFT: {{ $company['bank_swift'] }}</span>@endif
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
@else
    <td></td>
@endif
@if($invoice->terms)
    <td>
        <div class="notes-title">Terms &amp; Conditions</div>
        <div class="notes-content">{{ $invoice->terms }}</div>
    </td>
@else
    <td></td>
@endif
</tr>
</table>
@endif

<table class="signature-table">
<tr>
    <td>
        <div class="sig-label">Authorized Signature</div>
        <div class="sig-line"></div>
        <div class="sig-hint">Name &amp; Title</div>
    </td>
    <td>
        <div class="sig-label">Customer Signature</div>
        <div class="sig-line"></div>
        <div class="sig-hint">Name &amp; Date</div>
    </td>
</tr>
</table>

<table class="stamp-table">
<tr>
    <td>
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }}</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
