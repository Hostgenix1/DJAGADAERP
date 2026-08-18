@extends('layouts.app')
@section('title', 'New Shipment')

@php
    $preselectedOrder = null;
    if (!empty($preselectedOrderId)) {
        $preselectedOrder = \App\Models\Order::with('customer')->find($preselectedOrderId);
    }
@endphp

@section('content')
<form method="POST" action="{{ route('shipments.store') }}">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-shipping-fast mr-1"></i> New Shipment</h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Customer *</label>
                            <select name="customer_id" id="customer-select" class="form-control" required>
                                <option value="">-- Select --</option>
                                @foreach($customers as $id => $n)
                                    <option value="{{ $id }}">{{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Order</label>
                            <select name="order_id" id="order-select" class="form-control">
                                <option value="">-- Optional --</option>
                                @foreach($orders as $id => $n)
                                    <option value="{{ $id }}" @selected(isset($preselectedOrderId) && $preselectedOrderId == $id)>{{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Invoice</label>
                            <select name="invoice_id" class="form-control">
                                <option value="">-- Optional --</option>
                                @foreach($invoices as $id => $n)
                                    <option value="{{ $id }}">{{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Carrier</label>
                            <input type="text" name="carrier" class="form-control" placeholder="e.g. DHL, FedEx">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Tracking Number</label>
                            <input type="text" name="tracking_number" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Shipping Method *</label>
                            <select name="shipping_method" class="form-control" required>
                                <option value="air">Air</option>
                                <option value="sea">Sea</option>
                                <option value="land">Land</option>
                                <option value="courier">Courier</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Origin</label>
                            <input type="text" name="origin" class="form-control" placeholder="e.g. Shanghai, China">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Destination</label>
                            <input type="text" name="destination" class="form-control" placeholder="e.g. Antwerp, Belgium">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Status *</label>
                            <select name="status" class="form-control" required>
                                <option value="preparing">Preparing</option>
                                <option value="in_transit">In Transit</option>
                                <option value="customs">Customs</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Shipped At</label>
                            <input type="datetime-local" name="shipped_at" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Estimated Arrival</label>
                            <input type="date" name="estimated_arrival" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-block btn-sm"><i class="fas fa-save mr-1"></i> Save Shipment</button>
                    <a href="{{ route('shipments.index') }}" class="btn btn-default btn-block btn-sm">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
@if($preselectedOrder)
<script>
    $(function(){
        if (!$('#customer-select').val() && {{ $preselectedOrder->customer_id ?: 'null' }}) {
            $('#customer-select').val({{ $preselectedOrder->customer_id }});
        }
    });
</script>
@endif
@endsection
