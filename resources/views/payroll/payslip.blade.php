<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Payslip - {{ $payrollEntry->employee?->name }} - {{ $payrollEntry->period }}</title>
<style>
    @page { margin: 10mm 10mm 14mm; }
    * { font-family: 'Lato', 'DejaVu Sans', sans-serif; }
    body { font-size: 9.6pt; color: #1E293B; }

    .header-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
    .header-table td { vertical-align: top; }
    .logo { max-height: 60px; max-width: 220px; }
    .co-info { text-align: right; line-height: 1.45; }
    .co-name { font-size: 16px; font-weight: bold; color: #111827; }
    .co-line { font-size: 8.5pt; color: #475569; }

    .payslip-title { display: block; font-size: 15px; font-weight: bold; color: #111827; margin: 4px 0 10px; text-align: right; letter-spacing: 1px; }

    table.meta { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
    table.meta td { border: 1px solid #CBD5E1; padding: 6px 8px; vertical-align: top; }
    .box-title { font-size: 8.5pt; font-weight: bold; letter-spacing: 1.2px; color: #64748B; text-transform: uppercase; margin-bottom: 4px; }

    table.earn { width: 100%; border-collapse: collapse; margin: 6px 0; }
    table.earn th { background: #111827; color: #fff; padding: 6px; text-align: left; font-size: 9pt; letter-spacing: .4px; }
    table.earn td { padding: 6px; border-bottom: 1px solid #E2E8F0; font-size: 10pt; }
    table.earn td.num { text-align: right; }
    table.earn tr.total td { border-top: 2px solid #111827; font-weight: bold; font-size: 12pt; }

    .footer { margin-top: 16px; font-size: 8.5pt; color: #64748B; text-align: center; }
</style>
</head>
<body>

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
            @if($company['city'] || $company['country'])<div class="co-line">{{ trim($company['city'].(($company['city'] && $company['country']) ? ', ' : '').$company['country']) }}</div>@endif
            @if($company['phone'])<div class="co-line">Tel: {{ $company['phone'] }}</div>@endif
            @if($company['email'])<div class="co-line">Email: {{ $company['email'] }}</div>@endif
            @if(!empty($company['trn']))<div class="co-line">TRN: {{ $company['trn'] }}</div>@endif
        </td>
    </tr>
</table>

<div class="payslip-title">PAYSLIP — {{ $payrollEntry->period }}</div>

<table class="meta">
    <tr>
        <td style="width:50%;">
            <div class="box-title">Employee</div>
            <div style="font-weight:bold; font-size:11px;">{{ $payrollEntry->employee?->name }}</div>
            @if($payrollEntry->employee?->position)<div>{{ $payrollEntry->employee->position }}</div>@endif
            @if($payrollEntry->employee?->department)<div>{{ $payrollEntry->employee->department }}</div>@endif
            @if($payrollEntry->employee?->email)<div>Email: {{ $payrollEntry->employee->email }}</div>@endif
        </td>
        <td style="width:50%;">
            <div class="box-title">Period &amp; Status</div>
            <div>Period: <b>{{ $payrollEntry->period }}</b></div>
            <div>Status: <b>{{ ucfirst($payrollEntry->status) }}</b></div>
            @if($payrollEntry->pay_date)<div>Scheduled Pay Date: {{ $payrollEntry->pay_date->format('d M Y') }}</div>@endif
            @if($payrollEntry->paid_on)<div>Paid On: {{ $payrollEntry->paid_on->format('d M Y') }}</div>@endif
            <div>Currency: <b>{{ $payrollEntry->currency?->code ?? ($payrollEntry->employee?->currency?->code ?? '—') }}</b></div>
        </td>
    </tr>
</table>

<table class="earn">
    <tr><th style="width:60%;">Description</th><th class="num">Amount</th></tr>
    <tr><td>Base Salary</td><td class="num">{{ number_format((float) ($payrollEntry->base_salary ?: $payrollEntry->gross_salary), 2) }}</td></tr>
    @if($payrollEntry->transport_allowance > 0)
        <tr><td>Transport Allowance</td><td class="num">{{ number_format($payrollEntry->transport_allowance, 2) }}</td></tr>
    @endif
    @if($payrollEntry->bonus > 0)
        <tr><td>Bonus</td><td class="num">{{ number_format($payrollEntry->bonus, 2) }}</td></tr>
    @endif
    @if($payrollEntry->other_allowances > 0)
        <tr><td>Other Allowances</td><td class="num">{{ number_format($payrollEntry->other_allowances, 2) }}</td></tr>
    @elseif($payrollEntry->allowances > ($payrollEntry->transport_allowance + $payrollEntry->bonus + $payrollEntry->other_allowances))
        <tr><td>Allowances</td><td class="num">{{ number_format($payrollEntry->allowances, 2) }}</td></tr>
    @endif
    @if($payrollEntry->absence_deduction > 0)
        <tr><td>Absence Deduction ({{ rtrim(rtrim(number_format((float) $payrollEntry->absent_days, 2), '0'), '.') }} day(s))</td><td class="num">-{{ number_format($payrollEntry->absence_deduction, 2) }}</td></tr>
    @endif
    @if($payrollEntry->other_deductions > 0)
        <tr><td>Other Deductions</td><td class="num">-{{ number_format($payrollEntry->other_deductions, 2) }}</td></tr>
    @elseif($payrollEntry->deductions > ($payrollEntry->absence_deduction + $payrollEntry->other_deductions))
        <tr><td>Deductions</td><td class="num">-{{ number_format($payrollEntry->deductions, 2) }}</td></tr>
    @endif
    <tr class="total"><td>NET PAY</td><td class="num">{{ number_format($payrollEntry->net_salary, 2) }}</td></tr>
</table>

@if(($payrollEntry->present_days + $payrollEntry->absent_days + $payrollEntry->leave_days) > 0)
    <div style="margin-top:8px; font-size:9pt; color:#475569;">
        <b>Attendance ({{ $payrollEntry->period }}):</b>
        Present {{ rtrim(rtrim(number_format((float) $payrollEntry->present_days, 2), '0'), '.') }} day(s)
        • Absent {{ rtrim(rtrim(number_format((float) $payrollEntry->absent_days, 2), '0'), '.') }} day(s)
        • Leave {{ rtrim(rtrim(number_format((float) $payrollEntry->leave_days, 2), '0'), '.') }} day(s)
    </div>
@endif

@if($payrollEntry->notes)
    <div style="margin-top:8px; font-size:9pt; color:#475569;"><b>Notes:</b> {{ $payrollEntry->notes }}</div>
@endif

<div class="footer">This is a computer-generated payslip. For any queries please contact {{ $company['email'] ?: 'the HR department' }}.</div>

</body>
</html>