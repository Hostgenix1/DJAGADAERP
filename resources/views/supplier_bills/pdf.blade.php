<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>{{ $company['name'] }} - Supplier Bill {{ $bill->number }}</title>
<style>
    @page { margin: 10mm 10mm 14mm; }
    * { font-family: 'DejaVu Sans', sans-serif; }
    body { font-size: 10pt; color: #1E293B; }

    /* ── Header ── */
    .header-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
    .header-table td { vertical-align: top; }
    .logo { max-height: 165px; max-width: 260px; }
    .co-info { text-align: right; line-height: 1.45; }
    .co-name { font-size: 17px; font-weight: bold; color: #111827; }
    .co-line { font-size: 8.5pt; color: #475569; }
    .co-badge { font-size: 8pt; color: #64748B; }

    .inv-no-line { display: block; font-size: 14px; font-weight: bold; color: #111827; margin: 2px 0 10px; text-align: right; }

    /* ── 3-column meta ── */
    .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
    .meta-box { border: 1px solid #CBD5E1; border-top: 2px solid #111827; padding: 6px 8px; vertical-align: top; background: #fff; }
    .box-title { font-size: 8.5pt; font-weight: bold; letter-spacing: 1.2px; color: #111827; text-transform: uppercase; margin-bottom: 4px; }
    .label { font-weight: bold; color: #111827; }
    .meta-row { padding: 1px 0; line-height: 1.5; }
    .value { color: #1E293B; }
    .small { font-size: 8.5pt; }

    /* ── Items ── */
    table.items { width: 100%; border-collapse: collapse; margin: 6px 0; }
    table.items th { background: #111827; color: #fff; padding: 5px 6px; text-align: left; font-size: 9pt; letter-spacing: 0.4px; white-space: nowrap; }
    table.items th.num { text-align: right; }
    table.items td { padding: 5px 6px; border-bottom: 1px solid #E2E8F0; vertical-align: top; font-size: 10pt; }
    table.items td.num { text-align: right; white-space: nowrap; }
    table.items tr:nth-child(even) td { background: #F8FAFC; }
    .item-desc { font-weight: bold; color: #111827; }
    .sub-desc { font-size: 8pt; color: #64748B; margin-top: 1px; }
    .unit-cell { font-weight: bold; color: #111827; text-align: center; }

    /* ── Totals ── */
    .words { margin-top: 8px; font-size: 9.5pt; font-style: italic; color: #334155; border-top: 1px solid #E2E8F0; padding-top: 6px; }
    .words b { color: #111827; font-style: normal; text-transform: uppercase; letter-spacing: 0.6px; }

    table.totals { width: 260px; margin-left: auto; border-collapse: collapse; margin-top: 4px; }
    table.totals td { padding: 3px 6px; font-size: 10pt; }
    table.totals td:last-child { text-align: right; }
    table.totals tr.total-row td { border-top: 2px solid #111827; font-weight: bold; font-size: 12pt; color: #111827; }

    /* ── Terms ── */
    .terms-box { border: 1px solid #E2E8F0; padding: 6px 8px; margin-top: 8px; }
    .terms-title { font-size: 9pt; font-weight: bold; letter-spacing: 1px; color: #111827; text-transform: uppercase; margin-bottom: 3px; }
    .terms-text { font-size: 9pt; color: #475569; line-height: 1.5; text-align: justify; }

    /* ── Signature ── */
    .sig-table { width: 100%; border-collapse: collapse; margin-top: 12px; }
    .sig-table td { width: 50%; text-align: center; padding: 0 8px; }
    .sig-line { border-bottom: 1px dotted #94A3B8; height: 24px; margin-bottom: 2px; }
    .sig-img { max-height: 65px; max-width: 220px; object-fit: contain; margin-bottom: 2px; }
    .sig-name { font-size: 9.5pt; font-weight: bold; color: #111827; text-transform: uppercase; letter-spacing: 0.8px; }
    .sig-date { font-size: 8.5pt; color: #64748B; }
</style>
</head>
<body>

{{-- ═══ HEADER ═══ --}}
<table class="header-table">
    <tr>
        <td style="width:38%;">
            @if($company['show_logo'] && $company['logo_url'])
                <img src="{{ $company['logo_url'] }}" class="logo" alt="Logo">
            @else
                <div class="co-name">{{ $company['name'] }}</div>
            @endif
        </td>
        <td class="co-info">
            <div class="co-name">{{ $company['name'] }}</div>
            @if($company['address'])<div class="co-line">{{ $company['address'] }}</div>@endif
            @if(!empty($company['free_zone']))<div class="co-line">{{ $company['free_zone'] }}</div>@endif
            @if($company['city'] || $company['country'])<div class="co-line">{{ trim($company['city'].(($company['city'] && $company['country']) ? ', ' : '').$company['country']) }}</div>@endif
            @if($company['phone'])<div class="co-line">Tel: {{ $company['phone'] }}</div>@endif
            @if($company['email'])<div class="co-line">Email: {{ $company['email'] }}</div>@endif
            @if(!empty($company['trade_license']))<div class="co-line">License: {{ $company['trade_license'] }}</div>@endif
            @if(!empty($company['trn']))<div class="co-line">TRN: {{ $company['trn'] }}</div>@endif
            @if(!empty($company['registration']))<div class="co-line">Reg: {{ $company['registration'] }}</div>@endif
        </td>
    </tr>
</table>

<div class="inv-no-line">SUPPLIER BILL {{ $bill->number }}</div>

{{-- ═══ 3-COLUMN META ═══ --}}
<table class="meta-table">
    <tr>
        {{-- SUPPLIER --}}
        <td class="meta-box" style="width:34%;">
            <div class="box-title">Supplier</div>
            <div class="meta-row value" style="font-weight:bold; font-size:10px;">{{ $bill->supplier?->company_name }}</div>
            @if($bill->supplier?->address)<div class="meta-row value">{{ $bill->supplier->address }}</div>@endif
            @if($bill->supplier?->city || $bill->supplier?->country)
                <div class="meta-row value">{{ trim($bill->supplier->city.' '.($bill->supplier->country??'')) }}</div>
            @endif
            @if($bill->supplier?->email)<div class="meta-row value">Email: {{ $bill->supplier->email }}</div>@endif
            @if($bill->supplier?->phone)<div class="meta-row value">Tel: {{ $bill->supplier->phone }}</div>@endif
        </td>

        {{-- META --}}
        <td class="meta-box" style="width:33%; border-left:none;">
            <div class="box-title">Details</div>
            <div class="meta-row"><span class="label">Bill Date:</span> <span class="value">{{ $bill->bill_date?->format('d/m/Y') }}</span></div>
            @if($bill->due_date)<div class="meta-row"><span class="label">Due Date:</span> <span class="value">{{ $bill->due_date->format('d/m/Y') }}</span></div>@endif
            @if($bill->reference_no)<div class="meta-row"><span class="label">Reference No:</span> <span class="value">{{ $bill->reference_no }}</span></div>@endif
            @if($bill->purchaseOrder)<div class="meta-row"><span class="label">From PO:</span> <span class="value">{{ $bill->purchaseOrder->number }}</span></div>@endif
            @if($bill->payment_terms)<div class="meta-row"><span class="label">Payment Terms:</span> <span class="value">{{ $bill->payment_terms }}</span></div>@endif
        </td>

        {{-- BANK DETAILS --}}
        <td class="meta-box" style="width:33%; border-left:none;">
            <div class="box-title">Bank Details</div>
            @if(!empty($company['bank_account']))<div class="meta-row"><span class="label">Account Name:</span> <span class="value">{{ $company['bank_account'] }}</span></div>@endif
            @if(!empty($company['bank_iban']))<div class="meta-row"><span class="label">IBAN:</span> <span class="value">{{ $company['bank_iban'] }}</span></div>@endif
            @if(!empty($company['bank_number']))<div class="meta-row"><span class="label">Account No:</span> <span class="value">{{ $company['bank_number'] }}</span></div>@endif
            @if(!empty($company['bank_name']))<div class="meta-row"><span class="label">Bank Name:</span> <span class="value">{{ $company['bank_name'] }}</span></div>@endif
            @if(!empty($company['bank_swift']))<div class="meta-row"><span class="label">SWIFT Code:</span> <span class="value">{{ $company['bank_swift'] }}</span></div>@endif
            @if(!empty($company['bank_address']))<div class="meta-row"><span class="label">Address:</span> <span class="value">{{ $company['bank_address'] }}</span></div>@endif
        </td>
    </tr>
</table>

{{-- ═══ ITEMS ═══ --}}
<table class="items">
    <thead>
    <tr>
        <th style="width:4%;">#</th>
        <th>Description</th>
        <th class="num" style="width:9%;">Quantity</th>
        <th style="width:8%;">Unit</th>
        <th class="num" style="width:14%;">Unit Price</th>
        <th class="num" style="width:8%;">Taxes</th>
        <th class="num" style="width:14%;">Total Amount</th>
    </tr>
    </thead>
    <tbody>
    @foreach($bill->items as $i => $item)
        @php
            $effTax = $item->tax_rate ?? $bill->vat_rate;
            $effTax = ($item->tax_rate === null && $bill->vat_mode === 'none') ? 0 : $effTax;
            $effTax = $effTax ?? 0;
        @endphp
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>
                <div class="item-desc">{{ $item->description }}</div>
                @if($item->sub_description)<div class="sub-desc">{{ $item->sub_description }}</div>@endif
            </td>
            <td class="num">{{ rtrim(rtrim(\App\Support\SettingsHelper::formatMoney($item->qty), '0'), '.') }}</td>
            <td class="unit-cell">{{ $item->unit ?: '-' }}</td>
            <td class="num">{{ $bill->currency?->code }} {{ \App\Support\SettingsHelper::formatMoney($item->unit_price) }}@if($item->unit) / {{ $item->unit }}@endif</td>
            <td class="num">{{ $effTax > 0 ? rtrim(rtrim($effTax,'0'),'.').'%' : '0%' }}</td>
            <td class="num">{{ $bill->currency?->code }} {{ \App\Support\SettingsHelper::formatMoney($item->line_total * (1 + $effTax/100)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

{{-- ═══ WORDS + TOTALS ═══ --}}
<div class="words">
    <b>Total Amount in Words:</b>
    {{ \App\Support\NumberToWords::toWords($bill->total, \App\Support\SettingsHelper::wordsLang()) }} {{ $bill->currency?->code ?? '' }}
</div>

<table class="totals">
    <tr><td>Total Amount</td><td>{{ $bill->currency?->code }} {{ \App\Support\SettingsHelper::formatMoney($bill->subtotal + $bill->tax_amount) }}</td></tr>
    <tr><td>Tax ({{ $bill->vat_mode === 'included' ? 'Incl.' : ($bill->vat_rate ? rtrim(rtrim($bill->vat_rate,'0'),'.').'%' : '0%') }})</td><td>{{ $bill->currency?->code }} {{ \App\Support\SettingsHelper::formatMoney($bill->tax_amount) }}</td></tr>
    @if((float)$bill->discount > 0)
    <tr><td>Discount</td><td>- {{ $bill->currency?->code }} {{ \App\Support\SettingsHelper::formatMoney($bill->discount) }}</td></tr>
    @endif
    <tr class="total-row"><td>Total</td><td>{{ $bill->currency?->code }} {{ \App\Support\SettingsHelper::formatMoney($bill->total) }}</td></tr>
    @if((float)$bill->paid_amount > 0)
    <tr><td>Paid</td><td>- {{ $bill->currency?->code }} {{ \App\Support\SettingsHelper::formatMoney($bill->paid_amount) }}</td></tr>
    <tr class="total-row"><td>Balance Due</td><td>{{ $bill->currency?->code }} {{ \App\Support\SettingsHelper::formatMoney($bill->balance) }}</td></tr>
    @endif
</table>

{{-- ═══ TERMS ═══ --}}
@if($bill->notes || $company['footer'])
<div class="terms-box">
    <div class="terms-title">Terms &amp; Conditions</div>
    <div class="terms-text">{{ $bill->notes ?: $company['footer'] }}</div>
</div>
@endif

{{-- ═══ SIGNATURE ═══ --}}
<table class="sig-table">
    <tr>
        <td>
            @if($company['signature_url'])
                <img src="{{ $company['signature_url'] }}" class="sig-img" alt="Signature & Stamp">
            @else
                <div class="sig-line"></div>
            @endif
            <div class="sig-name">{{ $company['name'] }}</div>
            <div class="sig-date">Authorized Signature &amp; Stamp &nbsp;·&nbsp; Date: ___ / ___ / ______</div>
        </td>
        <td>
            <div class="sig-line"></div>
            <div class="sig-name">{{ $bill->supplier?->company_name }}</div>
            <div class="sig-date">Authorized Signature &amp; Stamp &nbsp;·&nbsp; Date: ___ / ___ / ______</div>
        </td>
    </tr>
</table>

</body>
</html>
