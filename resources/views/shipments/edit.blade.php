@extends('layouts.app')
@section('title', 'Edit '.$shipment->number)

@section('content')
<form method="POST" action="{{ route('shipments.update', $shipment) }}">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-ship mr-1"></i> Edit {{ $shipment->number }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Customer *</label>
                            <select name="customer_id" class="form-control" required>
                                <option value="">-- Select --</option>
                                @foreach($customers as $id => $n)
                                    <option value="{{ $id }}" {{ $shipment->customer_id == $id ? 'selected' : '' }}>{{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Order</label>
                            <select name="order_id" class="form-control">
                                <option value="">-- Optional --</option>
                                @foreach($orders as $id => $n)
                                    <option value="{{ $id }}" {{ $shipment->order_id == $id ? 'selected' : '' }}>{{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Invoice</label>
                            <select name="invoice_id" class="form-control">
                                <option value="">-- Optional --</option>
                                @foreach($invoices as $id => $n)
                                    <option value="{{ $id }}" {{ $shipment->invoice_id == $id ? 'selected' : '' }}>{{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>Number of Containers</label>
                            <input type="number" name="container_count" class="form-control" min="1" placeholder="e.g. 2, 8" value="{{ $shipment->container_count }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Container Type / Size</label>
                            <select name="container_size" class="form-control">
                                <option value="">-- Select --</option>
                                <option value="20ft" {{ $shipment->container_size === '20ft' ? 'selected' : '' }}>20 ft</option>
                                <option value="40ft" {{ $shipment->container_size === '40ft' ? 'selected' : '' }}>40 ft</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Carrier</label>
                            <input type="text" name="carrier" class="form-control" value="{{ $shipment->carrier }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Tracking / BL Number</label>
                            <input type="text" name="tracking_number" class="form-control" value="{{ $shipment->tracking_number }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Shipping Method *</label>
                            <select name="shipping_method" class="form-control" required>
                                @foreach(['air', 'sea', 'land', 'courier'] as $method)
                                    <option value="{{ $method }}" {{ $shipment->shipping_method === $method ? 'selected' : '' }}>{{ ucfirst($method) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Origin</label>
                            <input type="text" name="origin" class="form-control" value="{{ $shipment->origin }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Destination</label>
                            <input type="text" name="destination" class="form-control" value="{{ $shipment->destination }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Status *</label>
                            <select name="status" class="form-control" required>
                                @foreach(['preparing', 'in_transit', 'customs', 'delivered', 'cancelled'] as $s)
                                    <option value="{{ $s }}" {{ $shipment->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Shipped At</label>
                            <input type="datetime-local" name="shipped_at" class="form-control" value="{{ $shipment->shipped_at?->format('Y-m-d\TH:i') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Estimated Arrival</label>
                            <input type="date" name="estimated_arrival" class="form-control" value="{{ $shipment->estimated_arrival?->format('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ $shipment->notes }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-block btn-sm"><i class="fas fa-save mr-1"></i> Update</button>
                    <a href="{{ route('shipments.show', $shipment) }}" class="btn btn-default btn-block btn-sm">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
