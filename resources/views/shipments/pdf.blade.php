<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>{{ $company['name'] }} - Shipment {{ $shipment->number }}</title>
<style>
    @page { margin: 10mm 10mm 14mm; }
    * { font-family: 'DejaVu Sans', sans-serif; }
    body { font-size: 10pt; color: #1E293B; }

    .header-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
    .header-table td { vertical-align: top; }
    .logo { max-height: 60px; max-width: 220px; }
    .co-info { text-align: right; line-height: 1.45; }
    .co-name { font-size: 16px; font-weight: bold; color: #111827; }
    .co-line { font-size: 8.5pt; color: #475569; }

    .ship-title { display: block; font-size: 15px; font-weight: bold; color: #111827; margin: 4px 0 10px; text-align: right; letter-spacing: 1px; }

    table.meta { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
    table.meta td { border: 1px solid #CBD5E1; padding: 6px 8px; vertical-align: top; }
    .box-title { font-size: 8.5pt; font-weight: bold; letter-spacing: 1.2px; color: #64748B; text-transform: uppercase; margin-bottom: 4px; }

    table.det { width: 100%; border-collapse: collapse; margin: 6px 0; }
    table.det th { background: #111827; color: #fff; padding: 6px; text-align: left; font-size: 9pt; letter-spacing: .4px; }
    table.det td { padding: 6px; border-bottom: 1px solid #E2E8F0; font-size: 10pt; }
    table.det td:first-child { width: 35%; background: #F8FAFC; font-weight: bold; }

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

<div class="ship-title">SHIPMENT {{ $shipment->number }}</div>

<table class="meta">
    <tr>
        <td style="width:50%;">
            <div class="box-title">Customer</div>
            <div style="font-weight:bold; font-size:11px;">{{ $shipment->customer?->company_name }}</div>
            @if($shipment->customer?->address)<div>{{ $shipment->customer->address }}</div>@endif
            @if($shipment->customer?->city || $shipment->customer?->country)<div>{{ trim($shipment->customer->city.' '.($shipment->customer->country??'')) }}</div>@endif
            @if($shipment->customer?->email)<div>Email: {{ $shipment->customer->email }}</div>@endif
            @if($shipment->customer?->phone)<div>Tel: {{ $shipment->customer->phone }}</div>@endif
        </td>
        <td style="width:50%;">
            <div class="box-title">Shipment</div>
            <div>Status: <b>{{ strtoupper(str_replace('_', ' ', $shipment->status)) }}</b></div>
            <div>Method: <b>{{ ucfirst($shipment->shipping_method) }}</b></div>
            @if($shipment->shipped_at)<div>Shipped: {{ $shipment->shipped_at->format('d M Y H:i') }}</div>@endif
            @if($shipment->estimated_arrival)<div>Est. Arrival: {{ $shipment->estimated_arrival->format('d M Y') }}</div>@endif
            @if($shipment->delivered_at)<div>Delivered: {{ $shipment->delivered_at->format('d M Y H:i') }}</div>@endif
        </td>
    </tr>
</table>

<table class="det">
    <tr><th>Shipment Details</th><th></th></tr>
    <tr><td>Carrier</td><td>{{ $shipment->carrier ?? '-' }}</td></tr>
    <tr><td>Tracking / BL Number</td><td>{{ $shipment->tracking_number ?? '-' }}</td></tr>
    <tr><td>Number of Containers</td><td>{{ $shipment->container_count ? $shipment->container_count.' container'.($shipment->container_count > 1 ? 's' : '') : '-' }}</td></tr>
    <tr><td>Container Type / Size</td><td>{{ $shipment->container_size ? strtoupper($shipment->container_size) : '-' }}</td></tr>
    <tr><td>Origin</td><td>{{ $shipment->origin ?? '-' }}</td></tr>
    <tr><td>Destination</td><td>{{ $shipment->destination ?? '-' }}</td></tr>
    @if($shipment->order)<tr><td>Related Order</td><td>{{ $shipment->order->number }}</td></tr>@endif
    @if($shipment->invoice)<tr><td>Related Invoice</td><td>{{ $shipment->invoice->number }}</td></tr>@endif
    @if($shipment->notes)<tr><td>Notes</td><td>{{ $shipment->notes }}</td></tr>@endif
</table>

<div class="footer">Generated by DJAGADA ERP on {{ now()->format('d M Y H:i') }}.</div>

</body>
</html>