<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Payslip - {{ $payrollEntry->employee?->name }} - {{ $payrollEntry->period }}</title>
<style>
    @page { margin: 10mm 11mm 12mm; }
    * { font-family: 'Lato', 'DejaVu Sans', sans-serif; }
    body { font-size: 9.3pt; color: #1E293B; margin: 0; }

    .head { width: 100%; border-collapse: collapse; }
    .head td { vertical-align: top; }
    .logo { max-height: 58px; max-width: 210px; }
    .co-name { font-size: 15pt; font-weight: bold; color: #111827; }
    .co-line { font-size: 8.4pt; color: #475569; line-height: 1.4; }
    .title-box { text-align: right; }
    .title { font-size: 20pt; font-weight: bold; color: #1F2937; letter-spacing: 2px; margin: 0; }
    .title-sub { font-size: 10pt; font-weight: bold; color: #111827; margin-top: 2px; }
    .title-line { font-size: 8.6pt; color: #475569; }
    .rule { border-bottom: 2.2px solid #111827; margin: 5px 0 8px; }

    .section-title { font-size: 10.6pt; font-weight: bold; color: #111827; margin: 6px 0 3px; }

    table.grid { width: 100%; border-collapse: collapse; }
    table.grid td { width: 50%; vertical-align: top; padding-right: 14px; }
    .cell { border-bottom: 1px solid #CBD5E1; padding: 4px 0 3px; }
    .cell .lbl { font-size: 7.8pt; color: #64748B; letter-spacing: .3px; }
    .cell .val { font-size: 9.8pt; font-weight: bold; color: #111827; padding-top: 1px; }

    table.stats { width: 100%; border-collapse: separate; border-spacing: 6px 0; margin: 6px 0 2px; }
    table.stats td { background: #F1F5F9; border: 1px solid #E2E8F0; padding: 5px 8px; width: 25%; }
    table.stats .lbl { font-size: 7.8pt; color: #64748B; }
    table.stats .val { font-size: 10.5pt; font-weight: bold; color: #111827; padding-top: 1px; }

    table.cols { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin-top: 4px; }
    table.cols > tbody > tr > td { width: 50%; vertical-align: top; }
    table.items { width: 100%; border-collapse: collapse; }
    table.items th { background: #F1F5F9; border-bottom: 1.6px solid #111827; padding: 5px 6px; font-size: 8.4pt; color: #334155; text-align: left; }
    table.items th.num, table.items td.num { text-align: right; }
    table.items td { padding: 4px 6px; border-bottom: 1px solid #E2E8F0; font-size: 9.4pt; color: #111827; }
    table.items td .sub { font-size: 8pt; color: #64748B; }
    table.items tr.total td { border-top: 1.8px solid #111827; border-bottom: 2.4px solid #111827; background: #F1F5F9; font-weight: bold; font-size: 10.4pt; }

    table.netbar { width: 100%; border-collapse: collapse; margin-top: 8px; }
    table.netbar td { background: #3D8A3F; color: #FFFFFF; padding: 8px 14px; }
    .net-lbl { font-size: 13pt; font-weight: bold; letter-spacing: 1.5px; }
    .net-cur { font-size: 10.5pt; }
    .net-val { font-size: 17pt; font-weight: bold; text-align: right; }

    .words { border: 1px solid #E2E8F0; background: #FAFAFA; padding: 5px 10px; margin-top: 6px; font-size: 9pt; color: #334155; }
    .words b { color: #111827; }

    .sig { width: 100%; margin-top: 12px; }
    .sig .line { border-top: 1px solid #94A3B8; width: 220px; margin-left: auto; }
    .sig .cap { font-size: 8pt; color: #64748B; text-align: right; width: 220px; margin-left: auto; padding-top: 3px; }

    .footer { margin-top: 8px; border-top: 1px solid #E2E8F0; padding-top: 4px; font-size: 7.8pt; color: #64748B; text-align: center; }
</style>
</head>
<body>
@php
    $employee = $payrollEntry->employee;
    $currency = $payrollEntry->currency?->code ?? ($employee?->currency?->code ?? '');
    $periodStart = \Carbon\Carbon::createFromFormat('Y-m', $payrollEntry->period)->startOfMonth();
    $periodEnd = (clone $periodStart)->endOfMonth();
    $daysPaid = (float) $payrollEntry->present_days + (float) $payrollEntry->leave_days;
    $payDate = $payrollEntry->paid_on ?? $payrollEntry->pay_date;
    $dash = 'â€”';
@endphp

{{-- â•â•â• HEADER â•â•â• --}}
<table class="head">
    <tr>
        <td style="width:30%;">
            @if($company['show_logo'] && $company['logo_url'])
                <img src="{{ $company['logo_url'] }}" class="logo" alt="Logo">
            @else
                <div class="co-name">{{ $company['name'] }}</div>
            @endif
        </td>
        <td style="width:38%; padding-top:2px;">
            <div class="co-name" style="font-size:12.5pt;">{{ $company['name'] }}</div>
            @if($company['address'])<div class="co-line">{{ $company['address'] }}</div>@endif
            @if($company['city'] || $company['country'])<div class="co-line">{{ trim(($company['city'] ?? '').(($company['city'] && $company['country']) ? ', ' : '').($company['country'] ?? '')) }}</div>@endif
            @if($company['phone'])<div class="co-line">Tel: {{ $company['phone'] }}</div>@endif
            @if($company['email'])<div class="co-line">Email: {{ $company['email'] }}</div>@endif
            @if(!empty($company['license']))<div class="co-line">License: {{ $company['license'] }}</div>@endif
        </td>
        <td class="title-box" style="width:32%;">
            <div class="title">PAYSLIP</div>
            <div class="title-sub">{{ $periodStart->format('F Y') }}</div>
            <div class="title-line">{{ $periodStart->format('d M Y') }} &ndash; {{ $periodEnd->format('d M Y') }}</div>
            @if($payDate)<div class="title-line">Pay date: {{ $payDate->format('d M Y') }}</div>@endif
            <div class="title-line">Status: <strong>{{ ucfirst($payrollEntry->status) }}</strong></div>
        </td>
    </tr>
</table>
<div class="rule"></div>

{{-- â•â•â• EMPLOYEE DETAILS â•â•â• --}}
<div class="section-title">Employee details</div>
<table class="grid">
    <tr>
        <td>
            <div class="cell">
                <div class="lbl">Employee name</div>
                <div class="val">{{ $employee?->name ?? $dash }}</div>
            </div>
        </td>
        <td>
            <div class="cell">
                <div class="lbl">Employee ID</div>
                <div class="val">EMP-{{ str_pad((string) $payrollEntry->employee_id, 4, '0', STR_PAD_LEFT) }}</div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="cell">
                <div class="lbl">Designation</div>
                <div class="val">{{ $employee?->position ?: $dash }}</div>
            </div>
        </td>
        <td>
            <div class="cell">
                <div class="lbl">Department</div>
                <div class="val">{{ $employee?->department ?: $dash }}</div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="cell">
                <div class="lbl">Date of birth</div>
                <div class="val">{{ $employee?->date_of_birth?->format('d M Y') ?? $dash }}</div>
            </div>
        </td>
        <td>
            <div class="cell">
                <div class="lbl">Nationality</div>
                <div class="val">{{ $employee?->nationality ?: $dash }}</div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="cell">
                <div class="lbl">Emirates ID</div>
                <div class="val">{{ $maskedEmiratesId ?: $dash }}</div>
            </div>
        </td>
        <td>
            <div class="cell">
                <div class="lbl">Labour card no. / Person code</div>
                <div class="val">{{ $employee?->labour_card_no ?: $dash }}</div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="cell">
                <div class="lbl">Date of joining</div>
                <div class="val">{{ $employee?->hire_date?->format('d M Y') ?? $dash }}</div>
            </div>
        </td>
        <td></td>
    </tr>
</table>

{{-- â•â•â• ATTENDANCE STRIP â•â•â• --}}
<table class="stats">
    <tr>
        <td>
            <div class="lbl">Calendar days</div>
            <div class="val">{{ (int) ($payrollEntry->working_days ?: 30) }}</div>
        </td>
        <td>
            <div class="lbl">Days paid</div>
            <div class="val">{{ rtrim(rtrim(number_format($daysPaid, 2), '0'), '.') }}</div>
        </td>
        <td>
            <div class="lbl">Unpaid leave (days)</div>
            <div class="val">{{ rtrim(rtrim(number_format((float) $payrollEntry->absent_days, 2), '0'), '.') }}</div>
        </td>
        <td>
            <div class="lbl">Overtime (hours)</div>
            <div class="val">{{ rtrim(rtrim(number_format((float) $payrollEntry->overtime_hours, 2), '0'), '.') ?: '0' }}</div>
        </td>
    </tr>
</table>

{{-- â•â•â• EARNINGS / DEDUCTIONS â•â•â• --}}
<table class="cols">
    <tr>
        <td>
            <div class="section-title">Earnings</div>
            <table class="items">
                <tr><th>Description</th><th class="num">Amount ({{ $currency }})</th></tr>
                <tr>
                    <td>Basic salary</td>
                    <td class="num">@money($payrollEntry->gross_salary)</td>
                </tr>
                @if((float) $payrollEntry->transport_allowance > 0)
                    <tr><td>Transport allowance</td><td class="num">@money($payrollEntry->transport_allowance)</td></tr>
                @endif
                @if((float) $payrollEntry->bonus > 0)
                    <tr><td>Bonus</td><td class="num">@money($payrollEntry->bonus)</td></tr>
                @endif
                @if((float) $payrollEntry->overtime_amount > 0)
                    <tr>
                        <td>Overtime<div class="sub">{{ rtrim(rtrim(number_format((float) $payrollEntry->overtime_hours, 2), '0'), '.') }} hrs</div></td>
                        <td class="num">@money($payrollEntry->overtime_amount)</td>
                    </tr>
                @endif
                @if((float) $payrollEntry->other_allowances > 0)
                    <tr><td>Other allowance</td><td class="num">@money($payrollEntry->other_allowances)</td></tr>
                @endif
                <tr class="total">
                    <td>Gross earnings</td>
                    <td class="num">@money((float) $payrollEntry->gross_salary + (float) $payrollEntry->allowances)</td>
                </tr>
            </table>
        </td>
        <td>
            <div class="section-title">Deductions</div>
            <table class="items">
                <tr><th>Description</th><th class="num">Amount ({{ $currency }})</th></tr>
                @if((float) $payrollEntry->absence_deduction > 0)
                    <tr>
                        <td>Unpaid leave<div class="sub">{{ rtrim(rtrim(number_format((float) $payrollEntry->absent_days, 2), '0'), '.') }} day(s)</div></td>
                        <td class="num">@money($payrollEntry->absence_deduction)</td>
                    </tr>
                @endif
                @if((float) $payrollEntry->other_deductions > 0)
                    <tr><td>Other deductions</td><td class="num">@money($payrollEntry->other_deductions)</td></tr>
                @endif
                @if((float) $payrollEntry->absence_deduction <= 0 && (float) $payrollEntry->other_deductions <= 0)
                    <tr><td class="sub" colspan="2" style="color:#64748B;">No deductions this period.</td></tr>
                @endif
                <tr class="total">
                    <td>Total deductions</td>
                    <td class="num">@money($payrollEntry->deductions)</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{-- â•â•â• NET PAY â•â•â• --}}
<table class="netbar">
    <tr>
        <td class="net-lbl">NET PAY</td>
        <td class="net-val"><span class="net-cur">{{ $currency }}</span> @money($payrollEntry->net_salary)</td>
    </tr>
</table>

@if(!empty($inWords))
    <div class="words"><b>In words:</b> {{ $inWords }}</div>
@endif

{{-- â•â•â• PAYMENT DETAILS (hidden entirely when no data) â•â•â• --}}
@if($payment['bank_name'] || $payment['iban_masked'] || $payment['routing_code'] || $payment['wps_sif_reference'] || $payment['establishment_id'])
    <div class="section-title" style="margin-top:12px;">Payment details</div>
    <table class="grid">
        <tr>
            <td>
                <div class="cell">
                    <div class="lbl">Payment method</div>
                    <div class="val">Bank transfer (WPS)</div>
                </div>
            </td>
            <td>
                <div class="cell">
                    <div class="lbl">Bank</div>
                    <div class="val">{{ $payment['bank_name'] ?: $dash }}</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="cell">
                    <div class="lbl">IBAN</div>
                    <div class="val">{{ $payment['iban_masked'] ?: $dash }}</div>
                </div>
            </td>
            <td>
                @if($payment['routing_code'])
                    <div class="cell">
                        <div class="lbl">Routing code (WPS)</div>
                        <div class="val">{{ $payment['routing_code'] }}</div>
                    </div>
                @endif
            </td>
        </tr>
        <tr>
            <td>
                @if($payment['wps_sif_reference'])
                    <div class="cell">
                        <div class="lbl">WPS / SIF reference</div>
                        <div class="val">{{ $payment['wps_sif_reference'] }}</div>
                    </div>
                @endif
            </td>
            <td>
                @if($payment['establishment_id'])
                    <div class="cell">
                        <div class="lbl">Establishment ID</div>
                        <div class="val">{{ $payment['establishment_id'] }}</div>
                    </div>
                @endif
            </td>
        </tr>
    </table>
@endif

<div class="sig">
    <div class="line"></div>
    <div class="cap">Employee signature (optional)</div>
</div>

<div class="footer">
    This is a computer-generated payslip and does not require a signature.
    Confidential &ndash; for the named employee only.@if($company['email']) Queries: {{ $company['email'] }}.@endif
</div>
</body>
</html>
