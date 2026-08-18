@extends('layouts.app')

@section('title', $shipment->number)

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="{{ route('shipments.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Shipments
        </a>
    </div>
    <div class="d-flex">
        @if($shipment->status === 'preparing')
            <form method="POST" action="{{ route('shipments.update-status', $shipment) }}" class="d-inline mr-2">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="in_transit">
                <button class="btn btn-warning btn-sm" onclick="return confirm('Mark as In Transit?');"><i class="fas fa-ship mr-1"></i> Mark In Transit</button>
            </form>
        @endif
        @if(in_array($shipment->status, ['in_transit', 'customs']))
            <form method="POST" action="{{ route('shipments.update-status', $shipment) }}" class="d-inline mr-2">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="delivered">
                <button class="btn btn-success btn-sm" onclick="return confirm('Mark as Arrived (delivered)?');"><i class="fas fa-flag-checkered mr-1"></i> Mark Arrived</button>
            </form>
        @endif
        @if(in_array($shipment->status, ['preparing', 'in_transit', 'customs']))
            <a href="{{ route('shipments.edit', $shipment) }}" class="btn btn-info btn-sm mr-2">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
        @endif
        @if(in_array($shipment->status, ['preparing']))
            <form method="POST" action="{{ route('shipments.destroy', $shipment) }}" class="d-inline" onsubmit="return confirm('Delete this shipment?');">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash mr-1"></i> Delete</button>
            </form>
        @endif
    </div>
</div>

@if($shipment->status !== 'cancelled')
<div class="card card-outline shadow-sm mb-3">
    <div class="card-header py-2" style="background:#d1ecf1;">
        <h6 class="card-title mb-0 font-weight-bold" style="color:#0c5460;">
            <i class="fas fa-shipping-fast mr-2"></i>Shipping / Logistics Progress
            @if($shipment->status === 'delivered')
                <span class="badge badge-success ml-2"><i class="fas fa-check mr-1"></i>Delivered</span>
            @endif
            @if($shipment->status === 'customs')
                <span class="badge badge-warning ml-2"><i class="fas fa-landmark mr-1"></i>Customs Hold</span>
            @endif
        </h6>
    </div>
    <div class="card-body py-3">
        @include('partials.progress-tracker', ['steps' => $shipment->shippingSteps(), 'shipMoving' => $shipment->status === 'in_transit'])
    </div>
</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @php
                            $statusClass = match($shipment->status) {
                                'preparing' => 'secondary',
                                'in_transit' => 'info',
                                'customs' => 'warning',
                                'delivered' => 'success',
                                'cancelled' => 'dark',
                                default => 'secondary'
                            };
                        @endphp
                        <h4 class="mb-1" style="color: #1e3a5f;">{{ $shipment->number }}</h4>
                        <span class="badge badge-{{ $statusClass }} badge-pill px-3 py-1 mb-3">{{ strtoupper(str_replace('_', ' ', $shipment->status)) }}</span>
                        <p class="text-muted mb-0">{{ ucfirst($shipment->shipping_method) }} Shipment</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="mb-2">
                            <small class="text-muted">SHIPPED AT</small><br>
                            <strong>{{ $shipment->shipped_at?->format('d M Y H:i') ?? '-' }}</strong>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">EST. ARRIVAL</small><br>
                            <strong>{{ $shipment->estimated_arrival?->format('d M Y') ?? '-' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="card card-outline card-light" style="border-left: 4px solid #1e3a5f;">
                    <div class="card-body py-2">
                        <small class="text-muted text-uppercase font-weight-bold">Customer</small>
                        <div class="mt-1">
                            <strong class="text-dark">{{ $shipment->customer?->company_name ?? '-' }}</strong>
                            @if($shipment->customer?->email)<br><small class="text-muted"><i class="fas fa-envelope fa-xs mr-1"></i>{{ $shipment->customer->email }}</small>@endif
                            @if($shipment->customer?->phone)<br><small class="text-muted"><i class="fas fa-phone fa-xs mr-1"></i>{{ $shipment->customer->phone }}</small>@endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-outline card-light" style="border-left: 4px solid #6c757d;">
                    <div class="card-body py-2">
                        <small class="text-muted text-uppercase font-weight-bold">Route</small>
                        <div class="mt-1">
                            <strong class="text-dark">{{ $shipment->origin ?? '-' }} <i class="fas fa-arrow-right mx-1"></i> {{ $shipment->destination ?? '-' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-outline shadow-sm">
            <div class="card-header" style="background: #1e3a5f; color: #fff; border-radius: 0;">
                <h5 class="card-title mb-0"><i class="fas fa-info-circle mr-2"></i>Shipment Details</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <tbody>
                        <tr>
                            <td style="width:35%; background:#f8fafc;"><strong>Carrier</strong></td>
                            <td>{{ $shipment->carrier ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="background:#f8fafc;"><strong>Tracking Number</strong></td>
                            <td>{{ $shipment->tracking_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="background:#f8fafc;"><strong>Shipping Method</strong></td>
                            <td><span class="badge badge-light">{{ ucfirst($shipment->shipping_method) }}</span></td>
                        </tr>
                        <tr>
                            <td style="background:#f8fafc;"><strong>Origin</strong></td>
                            <td>{{ $shipment->origin ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="background:#f8fafc;"><strong>Destination</strong></td>
                            <td>{{ $shipment->destination ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="background:#f8fafc;"><strong>Status</strong></td>
                            <td><span class="badge badge-{{ $statusClass }}">{{ strtoupper(str_replace('_', ' ', $shipment->status)) }}</span></td>
                        </tr>
                        <tr>
                            <td style="background:#f8fafc;"><strong>Shipped At</strong></td>
                            <td>{{ $shipment->shipped_at?->format('d M Y H:i') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="background:#f8fafc;"><strong>Estimated Arrival</strong></td>
                            <td>{{ $shipment->estimated_arrival?->format('d M Y') ?? '-' }}</td>
                        </tr>
                        @if($shipment->delivered_at)
                        <tr>
                            <td style="background:#f8fafc;"><strong>Delivered At</strong></td>
                            <td class="font-weight-bold" style="color: #166534;">{{ $shipment->delivered_at->format('d M Y H:i') }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        @if($shipment->notes)
        <div class="card card-outline card-light">
            <div class="card-body">
                <small class="text-muted text-uppercase font-weight-bold">Notes</small>
                <div class="mt-1 text-dark" style="font-size: 13px;">{!! nl2br(e($shipment->notes)) !!}</div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card card-outline card-light shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-link mr-2"></i>Related</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <small class="text-muted">Shipment #</small>
                        <small class="font-weight-bold">{{ $shipment->number }}</small>
                    </li>
                    @if($shipment->order)
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <small class="text-muted">Order</small>
                        <a href="{{ route('orders.show', $shipment->order) }}"><small>{{ $shipment->order->number }}</small></a>
                    </li>
                    @endif
                    @if($shipment->invoice)
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <small class="text-muted">Invoice</small>
                        <a href="{{ route('invoices.show', $shipment->invoice) }}"><small>{{ $shipment->invoice->number }}</small></a>
                    </li>
                    @endif
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <small class="text-muted">Created</small>
                        <small>{{ $shipment->created_at?->format('d M Y H:i') }}</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
