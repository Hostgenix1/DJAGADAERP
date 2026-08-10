@extends('layouts.app')

@section('title', 'Purchase Order '.$purchaseOrder->number)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clipboard-list mr-1"></i> {{ $purchaseOrder->number }}
                    <span class="badge {{ $purchaseOrder->status_badge }} ml-2">{{ ucfirst($purchaseOrder->status) }}</span>
                </h3>
                <div class="card-tools">
                    @if($purchaseOrder->status === 'draft')
                        @can('update-purchase-orders')
                            <a href="{{ route('purchase_orders.edit', $purchaseOrder) }}" class="btn btn-sm btn-info"><i class="fas fa-pen mr-1"></i> Edit</a>
                            <form method="POST" action="{{ route('purchase_orders.status', $purchaseOrder) }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="confirmed">
                                <button class="btn btn-sm btn-primary"><i class="fas fa-check mr-1"></i> Confirm</button>
                            </form>
                        @endcan
                    @endif
                    @if(in_array($purchaseOrder->status, ['confirmed', 'received']))
                        @can('update-purchase-orders')
                            <form method="POST" action="{{ route('purchase_orders.status', $purchaseOrder) }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="received">
                                <button class="btn btn-sm btn-primary"><i class="fas fa-box-open mr-1"></i> Mark Received</button>
                            </form>
                        @endcan
                    @endif
                    @can('create-supplier-bills')
                        @if($purchaseOrder->status !== 'billed' && $purchaseOrder->status !== 'cancelled')
                            <form method="POST" action="{{ route('supplier_bills.convert', $purchaseOrder) }}" class="d-inline" onsubmit="return confirm('Create Supplier Bill from this PO? Items, prices and currency will be copied.');">
                                @csrf
                                <button class="btn btn-sm btn-success"><i class="fas fa-file-invoice-dollar mr-1"></i> Create Supplier Bill</button>
                            </form>
                        @endif
                    @endcan
                    @can('view-purchase-orders')
                        <a href="{{ route('purchase_orders.pdf', $purchaseOrder) }}" class="btn btn-sm btn-secondary"><i class="fas fa-file-pdf mr-1"></i> PDF</a>
                    @endcan
                    <a href="{{ route('purchase_orders.index') }}" class="btn btn-sm btn-default"><i class="fas fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Supplier</strong><br>
                        {{ $purchaseOrder->supplier?->company_name }}<br>
                        <small class="text-muted">{{ $purchaseOrder->supplier?->city }} {{ $purchaseOrder->supplier?->country }}</small>
                    </div>
                    <div class="col-md-4">
                        <strong>PO Date:</strong> {{ $purchaseOrder->po_date?->format('d M Y') }}<br>
                        <strong>Expected Delivery:</strong> {{ $purchaseOrder->expected_delivery?->format('d M Y') ?? '—' }}<br>
                        <strong>Reference:</strong> {{ $purchaseOrder->reference_no ?: '—' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Currency:</strong> {{ $purchaseOrder->currency?->code ?? 'Default' }}<br>
                        <strong>Payment Terms:</strong> {{ $purchaseOrder->payment_terms ?: '—' }}<br>
                        <strong>Delivery Terms:</strong> {{ $purchaseOrder->delivery_terms ?: '—' }}
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width:5%">#</th>
                                <th>Description</th>
                                <th class="text-right">Qty</th>
                                <th>Unit</th>
                                <th class="text-right">Unit Price</th>
                                <th class="text-right">Tax %</th>
                                <th class="text-right">Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($purchaseOrder->items as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <strong>{{ $item->description }}</strong>
                                    @if($item->sub_description)<div class="text-muted small">{{ $item->sub_description }}</div>@endif
                                </td>
                                <td class="text-right">{{ rtrim(rtrim(number_format($item->qty, 2), '0'), '.') }}</td>
                                <td>{{ $item->unit ?: '-' }}</td>
                                <td class="text-right">{{ $purchaseOrder->currency?->code }} {{ number_format($item->unit_price, 2) }}{{ $item->unit ? ' / '.$item->unit : '' }}</td>
                                <td class="text-right">{{ $item->tax_rate !== null ? rtrim(rtrim($item->tax_rate, '0'), '.').'%' : '—' }}</td>
                                <td class="text-right">{{ $purchaseOrder->currency?->code }} {{ number_format($item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        @if($purchaseOrder->notes)
                            <strong>Notes:</strong>
                            <p class="text-muted">{{ $purchaseOrder->notes }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0" style="width:auto;margin-left:auto">
                            <tr><td>Subtotal</td><td class="text-right">{{ $purchaseOrder->currency?->code }} {{ number_format($purchaseOrder->subtotal, 2) }}</td></tr>
                            <tr><td>Tax ({{ $purchaseOrder->vat_mode === 'included' ? 'Incl.' : ($purchaseOrder->vat_rate ? rtrim(rtrim($purchaseOrder->vat_rate,'0'),'.').'%' : '0%') }})</td><td class="text-right">{{ $purchaseOrder->currency?->code }} {{ number_format($purchaseOrder->tax_amount, 2) }}</td></tr>
                            @if((float)$purchaseOrder->discount > 0)
                            <tr><td>Discount</td><td class="text-right">- {{ $purchaseOrder->currency?->code }} {{ number_format($purchaseOrder->discount, 2) }}</td></tr>
                            @endif
                            <tr class="font-weight-bold"><td>Total</td><td class="text-right">{{ $purchaseOrder->currency?->code }} {{ number_format($purchaseOrder->total, 2) }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-outline card-info">
            <div class="card-header"><h5 class="card-title mb-0"><i class="fas fa-file-invoice-dollar mr-1"></i> Supplier Bills</h5></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Bill</th><th>Status</th><th class="text-right">Total</th></tr></thead>
                    <tbody>
                    @forelse($purchaseOrder->supplierBills as $bill)
                        <tr>
                            <td><a href="{{ route('supplier_bills.show', $bill) }}">{{ $bill->number }}</a></td>
                            <td><span class="badge {{ $bill->status_badge }}">{{ ucfirst($bill->status) }}</span></td>
                            <td class="text-right">{{ $bill->currency?->code }} {{ number_format($bill->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">No bills yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @include('documents.partials._upload', ['morphType' => 'purchase_order', 'morphClass' => 'App\\Models\\PurchaseOrder', 'entity' => $purchaseOrder, 'documents' => $purchaseOrder->documents])
    </div>
</div>
@endsection
