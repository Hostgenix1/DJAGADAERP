@extends('layouts.app')

@section('title', $order->number)

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Orders
        </a>
    </div>
    <div class="d-flex">
        @if(in_array($order->status, ['draft']))
            <a href="{{ route('orders.edit', $order) }}" class="btn btn-info btn-sm mr-2">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
        @endif
        @if(in_array($order->status, ['draft']))
            <form method="POST" action="{{ route('orders.update-status', $order) }}" class="d-inline mr-2">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="confirmed">
                <button class="btn btn-primary btn-sm" onclick="return confirm('Confirm this order?');"><i class="fas fa-check mr-1"></i> Confirm</button>
            </form>
        @endif
        @if($order->status === 'confirmed')
            <form method="POST" action="{{ route('orders.update-status', $order) }}" class="d-inline mr-2">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="processing">
                <button class="btn btn-warning btn-sm" onclick="return confirm('Start processing?');"><i class="fas fa-cog mr-1"></i> Process</button>
            </form>
        @endif
        @if($order->status === 'processing')
            <form method="POST" action="{{ route('orders.update-status', $order) }}" class="d-inline mr-2">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="loading">
                <button class="btn btn-primary btn-sm" onclick="return confirm('Mark as loading?');"><i class="fas fa-ship mr-1"></i> Start Loading</button>
            </form>
        @endif
        @if($order->status === 'loading')
            <form method="POST" action="{{ route('orders.update-status', $order) }}" class="d-inline mr-2">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="completed">
                <button class="btn btn-success btn-sm" onclick="return confirm('Loading complete — production finished?');"><i class="fas fa-check-double mr-1"></i> Complete</button>
            </form>
        @endif
        @if(in_array($order->status, ['loading', 'completed']))
            <a href="{{ route('shipments.create', ['order_id' => $order->id]) }}" class="btn btn-outline-primary btn-sm mr-2">
                <i class="fas fa-ship mr-1"></i> Create Shipment
            </a>
        @endif
        @if(!in_array($order->status, ['completed', 'cancelled']))
            <form method="POST" action="{{ route('orders.update-status', $order) }}" class="d-inline mr-2">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="cancelled">
                <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Cancel this order?');"><i class="fas fa-times mr-1"></i> Cancel</button>
            </form>
        @endif
        @if(in_array($order->status, ['draft', 'cancelled']))
            <form method="POST" action="{{ route('orders.destroy', $order) }}" class="d-inline" onsubmit="return confirm('Delete this order?');">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash mr-1"></i> Delete</button>
            </form>
        @endif
    </div>
</div>

@if($order->status !== 'cancelled')
<div class="card card-outline shadow-sm mb-3">
    <div class="card-header py-2" style="background:#fff3cd;">
        <h6 class="card-title mb-0 font-weight-bold" style="color:#856404;">
            <i class="fas fa-industry mr-2"></i>Production / Order Progress
            @if($order->status === 'completed')
                <span class="badge badge-success ml-2"><i class="fas fa-check mr-1"></i>Workflow Finished</span>
            @endif
        </h6>
    </div>
    <div class="card-body py-3">
        @include('partials.progress-tracker', ['steps' => $order->productionSteps()])
    </div>
</div>
@endif

@php $latestShipment = $order->shipments()->latest('id')->first(); @endphp
@if($latestShipment && $latestShipment->status !== 'cancelled')
<div class="card card-outline shadow-sm mb-3">
    <div class="card-header py-2" style="background:#d1ecf1;">
        <h6 class="card-title mb-0 font-weight-bold" style="color:#0c5460;">
            <i class="fas fa-ship mr-2"></i>Shipping / Logistics Progress
            <a href="{{ route('shipments.show', $latestShipment) }}" class="btn btn-outline-info btn-xs ml-2">Shipment {{ $latestShipment->number }}</a>
        </h6>
    </div>
    <div class="card-body py-3">
        @include('partials.progress-tracker', ['steps' => $latestShipment->shippingSteps(), 'shipMoving' => $latestShipment->status === 'in_transit'])
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
                            $statusClass = match($order->status) {
                                'completed' => 'success',
                                'confirmed' => 'info',
                                'processing' => 'warning',
                                'cancelled' => 'dark',
                                default => 'secondary'
                            };
                        @endphp
                        <h4 class="mb-1" style="color: #1e3a5f;">{{ $order->number }}</h4>
                        <span class="badge badge-{{ $statusClass }} badge-pill px-3 py-1 mb-3">{{ strtoupper($order->status) }}</span>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="mb-2">
                            <small class="text-muted">ORDER DATE</small><br>
                            <strong>{{ $order->order_date?->format('d M Y') ?: '-' }}</strong>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">EXPECTED DELIVERY</small><br>
                            <strong>{{ $order->expected_delivery?->format('d M Y') ?: '-' }}</strong>
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
                            <strong class="text-dark">{{ $order->customer?->company_name ?: '-' }}</strong>
                            @if($order->customer?->contact_person)<br><small class="text-muted">{{ $order->customer->contact_person }}</small>@endif
                            @if($order->customer?->address)<br><small class="text-muted">{{ $order->customer->address }}</small>@endif
                            @if($order->customer?->city || $order->customer?->country)<br><small class="text-muted">{{ $order->customer->city }}{{ ($order->customer->city && $order->customer->country) ? ', ' : '' }}{{ $order->customer->country }}</small>@endif
                            @if($order->customer?->email)<br><small class="text-muted"><i class="fas fa-envelope fa-xs mr-1"></i>{{ $order->customer->email }}</small>@endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                @php $svc = app(\App\Services\SettingsService::class); @endphp
                <div class="card card-outline card-light" style="border-left: 4px solid #6c757d;">
                    <div class="card-body py-2">
                        <small class="text-muted text-uppercase font-weight-bold">From</small>
                        <div class="mt-1">
                            <strong class="text-dark">{{ $svc->get('company_name') ?: 'Your Company' }}</strong>
                            @if($svc->get('company_address'))<br><small class="text-muted">{{ $svc->get('company_address') }}</small>@endif
                            @if($svc->get('company_city') || $svc->get('company_country'))<br><small class="text-muted">{{ $svc->get('company_city') }}{{ ($svc->get('company_city') && $svc->get('company_country')) ? ', ' : '' }}{{ $svc->get('company_country') }}</small>@endif
                            @if($svc->get('company_email'))<br><small class="text-muted"><i class="fas fa-envelope fa-xs mr-1"></i>{{ $svc->get('company_email') }}</small>@endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-outline shadow-sm">
            <div class="card-header" style="background: #1e3a5f; color: #fff; border-radius: 0;">
                <h5 class="card-title mb-0"><i class="fas fa-list mr-2"></i>Line Items</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead style="background: #f8fafc;">
                        <tr>
                            <th style="width:5%; border-top:none;">#</th>
                            <th style="width:30%; border-top:none;">DESCRIPTION</th>
                            <th style="width:8%; border-top:none; text-align:center;">QTY</th>
                            <th style="width:8%; border-top:none; text-align:center;">UNIT</th>
                            <th style="width:14%; border-top:none; text-align:right;">PRICE</th>
                            <th style="width:10%; border-top:none; text-align:center;">TAX</th>
                            <th style="width:12%; border-top:none; text-align:center;">DISC</th>
                            <th style="width:15%; border-top:none; text-align:right;">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->items as $i => $item)
                        <tr>
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td><strong>{{ $item->description }}</strong></td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td class="text-center"><small class="text-muted">{{ $item->unit }}</small></td>
                            <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-center">
                                @if($item->tax_rate > 0)
                                    <span class="badge badge-light">{{ $item->tax_rate }}%</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->discount_pct > 0)
                                    <span class="badge badge-light text-danger">{{ $item->discount_pct }}%</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-right font-weight-bold" style="color: #1e3a5f;">{{ number_format($item->line_total, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 d-block"></i>No items</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-7">
                @if($order->notes)
                <div class="card card-outline card-light">
                    <div class="card-body">
                        <small class="text-muted text-uppercase font-weight-bold">Notes</small>
                        <div class="mt-1 text-dark" style="font-size: 13px;">{!! nl2br(e($order->notes)) !!}</div>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-md-5">
                <div class="card card-outline shadow-sm">
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="text-right text-muted" style="width:50%;">Subtotal</td>
                                <td class="text-right font-weight-bold" style="width:50%;">{{ $order->currency?->symbol ?? '$' }} {{ number_format($order->subtotal, 2) }}</td>
                            </tr>
                            @if($order->tax_amount > 0)
                            <tr>
                                <td class="text-right text-muted">Tax</td>
                                <td class="text-right">{{ $order->currency?->symbol ?? '$' }} {{ number_format($order->tax_amount, 2) }}</td>
                            </tr>
                            @endif
                            @if($order->discount > 0)
                            <tr>
                                <td class="text-right text-muted">Discount</td>
                                <td class="text-right text-danger">-{{ $order->currency?->symbol ?? '$' }} {{ number_format($order->discount, 2) }}</td>
                            </tr>
                            @endif
                            <tr style="background: #1e3a5f;">
                                <td class="text-right text-white font-weight-bold" style="border:none;">TOTAL</td>
                                <td class="text-right text-white font-weight-bold" style="border:none; font-size: 16px;">{{ $order->currency?->symbol ?? '$' }} {{ number_format($order->total, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-outline card-light shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-info-circle mr-2"></i>Order Info</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <small class="text-muted">Order #</small>
                        <small class="font-weight-bold">{{ $order->number }}</small>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <small class="text-muted">Status</small>
                        <span class="badge badge-{{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <small class="text-muted">Currency</small>
                        <small class="font-weight-bold">{{ $order->currency?->code ?? 'USD' }}</small>
                    </li>
                    <li class="list-group-item d-flex justify-content-between py-2">
                        <small class="text-muted">Created</small>
                        <small>{{ $order->created_at?->format('d M Y H:i') }}</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
