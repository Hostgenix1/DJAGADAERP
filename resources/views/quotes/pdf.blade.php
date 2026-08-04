<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; color: #2d3748; line-height: 1.5; width: 750px; margin: 0 auto; padding: 25px; }
.top-bar { background: #1e3a5f; height: 6px; width: 100%; margin-bottom: 15px; }

.header-block { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
.header-block td { vertical-align: middle; padding: 12px 15px; }
.header-left { width: 55%; }
.header-right { width: 45%; text-align: right; }

.company-logo { height: 45px; margin-bottom: 5px; }
.company-name { font-size: 18px; font-weight: bold; color: #1e3a5f; }
.company-detail { font-size: 8.5px; color: #64748b; line-height: 1.7; }

.doc-title-block { padding: 10px 15px; background: #1e3a5f; color: #ffffff; margin-bottom: 12px; }
.doc-title-main { font-size: 20px; font-weight: bold; letter-spacing: 1px; }

.status-pill { padding: 3px 12px; font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #ffffff; border-radius: 3px; display: inline-block; margin-top: 4px; }
.st-draft { background: #64748b; } .st-sent { background: #1e40af; } .st-accepted { background: #166534; } .st-rejected { background: #991b1b; }

.meta-table { width: 100%; margin: 0 0 12px 0; border-collapse: collapse; border-top: 2px solid #e2e8f0; border-bottom: 2px solid #e2e8f0; }
.meta-table td { padding: 6px 0; font-size: 9px; color: #475569; }
.meta-table .label { font-weight: bold; color: #334155; width: 80px; }

.billing-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
.billing-table td { width: 50%; vertical-align: top; padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; }
.billing-label { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.2px; color: #94a3b8; margin-bottom: 3px; }
.billing-name { font-size: 12px; font-weight: bold; color: #1e3a5f; margin-bottom: 2px; }
.billing-detail { font-size: 9px; color: #475569; line-height: 1.7; }

.items-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
.items-table th { background: #1e3a5f; color: #ffffff; padding: 8px 6px; font-size: 7.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
.items-table td { padding: 7px 6px; border-bottom: 1px solid #e2e8f0; font-size: 9px; }
.items-table tr:nth-child(even) td { background: #f8fafc; }
.items-table tr:nth-child(odd) td { background: #ffffff; }
.col-num { width: 4%; text-align: center; color: #94a3b8; }
.col-desc { width: 34%; }
.col-qty { width: 7%; text-align: center; }
.col-unit { width: 7%; text-align: center; color: #64748b; }
.col-price { width: 14%; text-align: right; }
.col-total { width: 14%; text-align: right; font-weight: bold; }

.totals-box { width: 100%; margin-bottom: 18px; }
.totals-table { width: 270px; border-collapse: collapse; margin-left: auto; }
.totals-table td { padding: 5px 10px; font-size: 9px; }
.totals-table .label-col { text-align: left; color: #64748b; }
.totals-table .value-col { text-align: right; font-weight: bold; }
.totals-table .sep td { border-bottom: 1px solid #e2e8f0; }
.totals-table .total-row td { background: #1e3a5f; color: #ffffff; font-size: 11px; font-weight: bold; padding: 8px 10px; }

.payment-strip { background: #f1f5f9; padding: 10px 14px; margin-bottom: 15px; border-left: 4px solid #1e3a5f; }
.payment-strip-title { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-bottom: 3px; }
.payment-strip-detail { font-size: 9px; color: #475569; }
.payment-strip-detail span { margin-right: 15px; }

.notes-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
.notes-table td { width: 50%; vertical-align: top; padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; }
.notes-title { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-bottom: 3px; }
.notes-content { font-size: 9px; color: #475569; line-height: 1.7; }

.signature-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
.signature-table td { width: 50%; vertical-align: top; padding: 12px 18px; }
.sig-label { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-bottom: 2px; }
.sig-line { border-bottom: 1px solid #cbd5e1; height: 35px; margin-bottom: 4px; }
.sig-hint { font-size: 7.5px; color: #cbd5e1; }

.stamp-area { border: 2px dashed #cbd5e1; border-radius: 8px; padding: 15px; width: 180px; height: 85px; margin: 12px auto; text-align: center; display: flex; align-items: center; justify-content: center; flex-direction: column; }
.stamp-text { font-size: 8px; color: #cbd5e1; text-transform: uppercase; letter-spacing: 1px; }

.footer-line { border-top: 2px solid #e2e8f0; padding-top: 10px; margin-top: 20px; text-align: center; }
.footer-company { font-size: 10px; font-weight: bold; color: #1e3a5f; margin-bottom: 2px; }
.footer-text { font-size: 8px; color: #94a3b8; }
</style>
</head>
<body>

<div class="top-bar"></div>

<table class="header-block">
<tr>
    <td class="header-left">
        @if(!empty($company['show_logo']) && !empty($company['logo_url']))
            <img src="{{ $company['logo_url'] }}" class="company-logo"><br>
        @endif
        <div class="company-name">{{ $company['name'] ?? 'Your Company' }}</div>
        <div class="company-detail">
            @if($company['address']) {{ $company['address'] }}<br>@endif
            @if($company['city']) {{ $company['city'] }}@if($company['country']), {{ $company['country'] }}@endif<br>@endif
            @if(!empty($company['trn'])) TRN: {{ $company['trn'] }}<br>@endif
            @if(!empty($company['trade_license'])) Trade License: {{ $company['trade_license'] }}<br>@endif
            @if(!empty($company['free_zone'])) Free Zone: {{ $company['free_zone'] }}<br>@endif
            @if(!empty($company['entity_type'])) {{ $company['entity_type'] }}<br>@endif
            @if($company['email']) Email: {{ $company['email'] }}<br>@endif
            @if($company['phone']) Phone: {{ $company['phone'] }}<br>@endif
        </div>
    </td>
    <td class="header-right">
        @php
            $sc = match($quote->status) { 'draft'=>'st-draft', 'sent'=>'st-sent', 'accepted'=>'st-accepted', 'rejected'=>'st-rejected', default=>'st-draft' };
        @endphp
        <div style="font-size: 20px; font-weight: bold; color: #1e3a5f; letter-spacing: 1px;">QUOTATION</div>
        <div style="font-size: 13px; color: #475569; margin-top: 6px;">{{ $quote->number }}</div>
        <span class="status-pill {{ $sc }}">{{ strtoupper($quote->status) }}</span>
    </td>
</tr>
</table>

<table class="meta-table">
<tr>
    <td class="label">Date</td>
    <td>{{ $quote->date?->format('d M Y') ?: '-' }}</td>
    <td class="label">Valid Until</td>
    <td>{{ $quote->valid_until?->format('d M Y') ?: '-' }}</td>
    <td class="label">Currency</td>
    <td>{{ $quote->currency?->code ?? 'AED' }}</td>
</tr>
<tr>
    <td class="label">Revision</td>
    <td>{{ $quote->revision }}</td>
    <td class="label"></td><td></td>
    <td class="label"></td><td></td>
</tr>
</table>

<table class="billing-table">
<tr>
    <td>
        <div class="billing-label">Quote To</div>
        <div class="billing-name">{{ $quote->customer?->company_name ?: '-' }}</div>
        <div class="billing-detail">
            @if($quote->customer?->contact_person) {{ $quote->customer->contact_person }}<br>@endif
            @if($quote->customer?->address) {{ $quote->customer->address }}<br>@endif
            @if($quote->customer?->city || $quote->customer?->country) {{ $quote->customer->city }}{{ ($quote->customer->city && $quote->customer->country) ? ', ' : '' }}{{ $quote->customer->country }}<br>@endif
            @if($quote->customer?->email) {{ $quote->customer->email }}<br>@endif
            @if($quote->customer?->phone) {{ $quote->customer->phone }}<br>@endif
        </div>
    </td>
    <td>
        <div class="billing-label">From</div>
        <div class="billing-name">{{ $company['name'] ?? '-' }}</div>
        <div class="billing-detail">
            @if($company['address']) {{ $company['address'] }}<br>@endif
            @if($company['city']) {{ $company['city'] }}{{ ($company['city'] && $company['country']) ? ', ' : '' }}{{ $company['country'] }}<br>@endif
            @if(!empty($company['trn'])) TRN: {{ $company['trn'] }}<br>@endif
            @if(!empty($company['trade_license'])) Trade License: {{ $company['trade_license'] }}<br>@endif
            @if($company['email']) {{ $company['email'] }}<br>@endif
            @if($company['phone']) {{ $company['phone'] }}@endif
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
    <th class="col-price">UNIT PRICE</th>
    <th class="col-total">TOTAL</th>
</tr>
</thead>
<tbody>
@forelse($quote->items as $i => $item)
<tr>
    <td class="col-num">{{ $i + 1 }}</td>
    <td class="col-desc">{{ $item->description }}</td>
    <td class="col-qty">{{ $item->qty }}</td>
    <td class="col-unit">{{ $item->unit }}</td>
    <td class="col-price">{{ $quote->currency?->symbol ?? ' }} {{ number_format($item->unit_price, 2) }}</td>
    <td class="col-total">{{ $quote->currency?->symbol ?? ' }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($item->unit_price, 2) }}</td>
    <td class="col-total">{{ $quote->currency?->symbol ?? ' }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($item->unit_price, 2) }}</td>
    <td class="col-total">{{ $quote->currency?->symbol ?? ' }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($item->unit_price, 2) }}</td>
    <td class="col-total">{{ $quote->currency?->symbol ?? ' }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($item->unit_price, 2) }}</td>
    <td class="col-total">{{ $quote->currency?->symbol ?? ' }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($item->unit_price, 2) }}</td>
    <td class="col-total">{{ $quote->currency?->symbol ?? ' }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($item->unit_price, 2) }}</td>
    <td class="col-total">{{ $quote->currency?->symbol ?? ' }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($item->line_total, 2) }}</td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center; color:#94a3b8; padding:20px;">No items</td></tr>
@endforelse
</tbody>
</table>

<div class="totals-box">
<table class="totals-table">
    <tr class="sep"><td class="label-col">Subtotal</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->subtotal, 2) }}</td></tr>
    @if($quote->tax_amount > 0)
        <tr class="sep"><td class="label-col">VAT 5%</td><td class="value-col">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->code == 'AED' ? 'AED' : ($quote->currency?->symbol ?? 'AED') }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->tax_amount, 2) }}</td></tr>
    @endif
    @if($quote->discount > 0)
        <tr class="sep"><td class="label-col">Discount</td><td class="value-col" style="color:#991b1b;">-{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->discount, 2) }}</td></tr>
    @endif
    <tr class="total-row"><td class="label-col" style="color:#fff;">TOTAL</td><td class="value-col" style="color:#fff;">{{ $quote->currency?->symbol ?? ' }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
 }} {{ number_format($quote->total, 2) }}</td></tr>
</table>
</div>

@if(!empty($bankAccount))
<div class="payment-strip">
    <div class="payment-strip-title">Bank Details</div>
    <div class="payment-strip-detail">
        @if(!empty($bankAccount->bank_name))<span>Bank: {{ $bankAccount->bank_name }}</span>@endif
        @if(!empty($bankAccount->account_number))<span>Account No: {{ $bankAccount->account_number }}</span>@endif
        @if(!empty($bankAccount->iban))<span>IBAN: {{ $bankAccount->iban }}</span>@endif
        @if(!empty($bankAccount->swift_code))<span>SWIFT: {{ $bankAccount->swift_code }}</span>@endif
    </div>
</div>
@endif

@if($quote->notes || $quote->terms)
<table class="notes-table">
<tr>
    @if($quote->notes)
        <td>
            <div class="notes-title">Notes</div>
            <div class="notes-content">{{ $quote->notes }}</div>
        </td>
    @else <td></td>
    @endif
    @if($quote->terms)
        <td>
            <div class="notes-title">Terms &amp; Conditions</div>
            <div class="notes-content">{{ $quote->terms }}</div>
        </td>
    @else <td></td>
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

<table style="width:100%; border-collapse:collapse; margin-bottom: 15px;">
<tr>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Company Stamp</div>
        </div>
    </td>
    <td style="width:50%; text-align:center; padding:10px;">
        <div class="stamp-area">
            <div class="stamp-text">Customer Stamp</div>
        </div>
    </td>
</tr>
</table>

<div class="footer-line">
    <div class="footer-company">{{ $company['name'] ?? '' }} @if(!empty($company['entity_type'])) | {{ $company['entity_type'] }} @endif</div>
    @if(!empty($company['footer']))<div class="footer-text">{{ $company['footer'] }}</div>@endif
    <div class="footer-text">Generated by DJAGADA ERP</div>
</div>

</body>
</html>
