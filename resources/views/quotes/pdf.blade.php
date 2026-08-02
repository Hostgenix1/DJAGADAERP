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
    .title-box { width: 40%; text-align: right; }
    .doc-title { font-size: 30px; font-weight: bold; color: #1e3a5f; letter-spacing: 2px; margin-bottom: 5px; }
    .doc-number { font-size: 14px; color: #475569; margin-bottom: 8px; }
    .status-pill { padding: 4px 14px; font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #ffffff; border-radius: 3px; }
    .st-draft { background: #64748b; } .st-sent { background: #1e40af; } .st-accepted { background: #166534; } .st-rejected { background: #991b1b; }
    .meta-table { width: 100%; margin: 10px 0 15px 0; border-collapse: collapse; border-top: 2px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; }
    .meta-table td { padding: 7px 0; font-size: 9.5px; color: #475569; }
    .meta-table .lbl { font-weight: bold; color: #334155; width: 75px; }
    .billing-table { width: 100%; margin-bottom: 18px; border-collapse: collapse; }
    .billing-table td { width: 50%; vertical-align: top; padding: 14px; background: #f8fafc; border: 1px solid #e2e8f0; }
    .billing-lbl { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.2px; color: #94a3b8; margin-bottom: 7px; }
    .billing-name { font-size: 13px; font-weight: bold; color: #1e3a5f; margin-bottom: 4px; }
    .billing-detail { font-size: 9.5px; color: #475569; line-height: 1.7; }
    .items-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
    .items-table th { background: #1e3a5f; color: #ffffff; padding: 9px 7px; font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
    .items-table td { padding: 8px 7px; border-bottom: 1px solid #e2e8f0; font-size: 9.5px; }
    .items-table tr:nth-child(even) td { background: #f8fafc; }
    .items-table tr:nth-child(odd) td { background: #ffffff; }
    .cn { width: 4%; text-align: center; color: #94a3b8; }
    .cd { width: 34%; } .cq { width: 7%; text-align: center; }
    .cu { width: 7%; text-align: center; color: #64748b; }
    .cp { width: 14%; text-align: right; } .ct { width: 14%; text-align: right; font-weight: bold; }
    .totals-box { width: 100%; margin-bottom: 18px; }
    .totals-table { width: 280px; border-collapse: collapse; margin-left: auto; }
    .totals-table td { padding: 5px 12px; font-size: 9.5px; }
    .totals-table .lc { text-align: left; color: #64748b; }
    .totals-table .vc { text-align: right; font-weight: bold; }
    .totals-table .sep td { border-bottom: 1px solid #e2e8f0; }
    .totals-table .total-row td { background: #1e3a5f; color: #ffffff; font-size: 12px; font-weight: bold; padding: 9px 12px; }
    .notes-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    .notes-table td { width: 50%; vertical-align: top; padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; }
    .notes-title { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-bottom: 5px; }
    .notes-content { font-size: 9.5px; color: #475569; line-height: 1.7; }
    .sig-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    .sig-table td { width: 50%; vertical-align: top; padding: 15px 20px; }
    .sig-label { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-bottom: 5px; }
    .sig-line { border-bottom: 1px solid #cbd5e1; height: 40px; margin-bottom: 5px; }
    .sig-hint { font-size: 8px; color: #cbd5e1; }
    .stamp-area { border: 2px dashed #cbd5e1; border-radius: 8px; padding: 20px; width: 200px; height: 100px; margin: 15px auto; text-align: center; }
    .stamp-text { font-size: 9px; color: #cbd5e1; text-transform: uppercase; letter-spacing: 1px; line-height: 60px; }
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
    <td class="title-box">
        <div class="doc-title">QUOTATION</div>
        <div class="doc-number">{{ $quote->number }}</div>
        @php
            $sc = match($quote->status) { 'draft'=>'st-draft', 'sent'=>'st-sent', 'accepted'=>'st-accepted', 'rejected'=>'st-rejected', default=>'st-draft' };
        @endphp
        <span class="status-pill {{ $sc }}">{{ strtoupper($quote->status) }}</span>
    </td>
</tr>
</table>

<table class="meta-table">
<tr>
    <td class="lbl">Date</td><td>{{ $quote->date?->format('d M Y') ?: '-' }}</td>
    <td class="lbl">Valid Until</td><td>{{ $quote->valid_until?->format('d M Y') ?: '-' }}</td>
    <td class="lbl">Currency</td><td>{{ $quote->currency?->code ?? 'USD' }}</td>
</tr>
<tr>
    <td class="lbl">Revision</td><td>{{ $quote->revision }}</td>
    <td class="lbl"></td><td></td>
    <td class="lbl"></td><td></td>
</tr>
</table>

<table class="billing-table">
<tr>
    <td>
        <div class="billing-lbl">Quote To</div>
        <div class="billing-name">{{ $quote->customer?->company_name ?: '-' }}</div>
        <div class="billing-detail">
            @if($quote->customer?->contact_person) {{ $quote->customer->contact_person }}<br>@endif
            @if($quote->customer?->address) {{ $quote->customer->address }}<br>@endif
            @if($quote->customer?->city || $quote->customer?->country) {{ $quote->customer->city }}{{ ($quote->customer->city && $quote->customer->country) ? ', ' : '' }}{{ $quote->customer->country }}<br>@endif
            @if($quote->customer?->email) {{ $quote->customer->email }}<br>@endif
            @if($quote->customer?->phone) {{ $quote->customer->phone }}@endif
        </div>
    </td>
    <td>
        <div class="billing-lbl">From</div>
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
<thead><tr>
    <th class="cn">#</th><th class="cd">DESCRIPTION</th><th class="cq">QTY</th><th class="cu">UNIT</th><th class="cp">PRICE</th><th class="ct">TOTAL</th>
</tr></thead>
<tbody>
@forelse($quote->items as $i => $item)
<tr>
    <td class="cn">{{ $i + 1 }}</td>
    <td class="cd">{{ $item->description }}</td>
    <td class="cq">{{ $item->qty }}</td>
    <td class="cu">{{ $item->unit }}</td>
    <td class="cp">{{ $quote->currency?->symbol ?? '$' }} {{ number_format($item->unit_price, 2) }}</td>
    <td class="ct">{{ $quote->currency?->symbol ?? '$' }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="lc">Subtotal</td><td class="vc">{{ $quote->currency?->symbol ?? '$' }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="lc">Tax</td><td class="vc">{{ $quote->currency?->symbol ?? '$' }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="lc">Discount</td><td class="vc" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? '$' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="lc" style="color:#fff;">TOTAL</td><td class="vc" style="color:#fff;">{{ $quote->currency?->symbol ?? '$' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($company['bank_name']) || !empty($company['bank_iban']))
<div style="background:#f1f5f9; padding:10px 16px; margin-bottom:15px; border-left:4px solid #1e3a5f;">
    <div style="font-size:8px; font-weight:bold; text-transform:uppercase; letter-spacing:0.5px; color:#94a3b8; margin-bottom:5px;">Payment Details</div>
    <div style="font-size:9.5px; color:#475569;">
        @if(!empty($company['bank_name']))<span style="margin-right:18px;">Bank: {{ $company['bank_name'] }}</span>@endif
        @if(!empty($company['bank_account']))<span style="margin-right:18px;">Account: {{ $company['bank_account'] }}</span>@endif
        @if(!empty($company['bank_iban']))<span style="margin-right:18px;">IBAN: {{ $company['bank_iban'] }}</span>@endif
        @if(!empty($company['bank_swift']))<span>SWIFT: {{ $company['bank_swift'] }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td><div class="notes-title">Notes</div><div class="notes-content">{{ $quote->notes }}</div></td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td><div class="notes-title">Terms &amp; Conditions</div><div class="notes-content">{{ $quote->terms }}</div></td>
    @else <td></td>
    @endif
</tr>
</table>
@endif

<table class="sig-table">
<tr>
    <td><div class="sig-label">Authorized Signature</div><div class="sig-line"></div><div class="sig-hint">Name &amp; Title</div></td>
    <td><div class="sig-label">Customer Signature</div><div class="sig-line"></div><div class="sig-hint">Name &amp; Date</div></td>
</tr>
</table>

<div style="text-align:center; margin-bottom:15px;">
    <div class="stamp-area"><div class="stamp-text">Company Stamp</div></div>
</div>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }}</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
