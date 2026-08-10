@extends('layouts.app')

@section('title', 'Supplier Bill '.$supplierBill->number)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-invoice-dollar mr-1"></i> {{ $supplierBill->number }}
                    <span class="badge {{ $supplierBill->status_badge }} ml-2">{{ ucfirst($supplierBill->status) }}</span>
                </h3>
                <div class="card-tools">
                    @if($supplierBill->status === 'draft')
                        @can('update-supplier-bills')
                            <a href="{{ route('supplier_bills.edit', $supplierBill) }}" class="btn btn-sm btn-info"><i class="fas fa-pen mr-1"></i> Edit</a>
                            <form method="POST" action="{{ route('supplier_bills.status', $supplierBill) }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="confirmed">
                                <button class="btn btn-sm btn-primary"><i class="fas fa-check mr-1"></i> Confirm</button>
                            </form>
                        @endcan
                    @endif
                    @if($supplierBill->status === 'confirmed')
                        @can('create-payments')
                            <a href="{{ route('payments.create', ['type' => 'supplier', 'supplier_id' => $supplierBill->supplier_id, 'bill_id' => $supplierBill->id]) }}" class="btn btn-sm btn-success"><i class="fas fa-hand-holding-usd mr-1"></i> Record Payment</a>
                        @endcan
                    @endif
                    @can('view-supplier-bills')
                        <a href="{{ route('supplier_bills.pdf', $supplierBill) }}" class="btn btn-sm btn-secondary"><i class="fas fa-file-pdf mr-1"></i> PDF</a>
                    @endcan
                    <a href="{{ route('supplier_bills.index') }}" class="btn btn-sm btn-default"><i class="fas fa-arrow-left mr-1"></i> Back</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Supplier</strong><br>
                        {{ $supplierBill->supplier?->company_name }}<br>
                        <small class="text-muted">{{ $supplierBill->supplier?->city }} {{ $supplierBill->supplier?->country }}</small>
                    </div>
                    <div class="col-md-4">
                        <strong>Bill Date:</strong> {{ $supplierBill->bill_date?->format('d M Y') }}<br>
                        <strong>Due Date:</strong> {{ $supplierBill->due_date?->format('d M Y') ?? '—' }}<br>
                        <strong>Reference:</strong> {{ $supplierBill->reference_no ?: '—' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Currency:</strong> {{ $supplierBill->currency?->code ?? 'Default' }}<br>
                        <strong>Payment Terms:</strong> {{ $supplierBill->payment_terms ?: '—' }}<br>
                        @if($supplierBill->purchaseOrder)
                            <strong>From PO:</strong> <a href="{{ route('purchase_orders.show', $supplierBill->purchaseOrder) }}">{{ $supplierBill->purchaseOrder->number }}</a>
                        @endif
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
                        @foreach($supplierBill->items as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <strong>{{ $item->description }}</strong>
                                    @if($item->sub_description)<div class="text-muted small">{{ $item->sub_description }}</div>@endif
                                </td>
                                <td class="text-right">{{ rtrim(rtrim(number_format($item->qty, 2), '0'), '.') }}</td>
                                <td>{{ $item->unit ?: '-' }}</td>
                                <td class="text-right">{{ $supplierBill->currency?->code }} {{ number_format($item->unit_price, 2) }}{{ $item->unit ? ' / '.$item->unit : '' }}</td>
                                <td class="text-right">{{ $item->tax_rate !== null ? rtrim(rtrim($item->tax_rate, '0'), '.').'%' : '—' }}</td>
                                <td class="text-right">{{ $supplierBill->currency?->code }} {{ number_format($item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        @if($supplierBill->notes)
                            <strong>Notes:</strong>
                            <p class="text-muted">{{ $supplierBill->notes }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0" style="width:auto;margin-left:auto">
                            <tr><td>Subtotal</td><td class="text-right">{{ $supplierBill->currency?->code }} {{ number_format($supplierBill->subtotal, 2) }}</td></tr>
                            <tr><td>Tax ({{ $supplierBill->vat_mode === 'included' ? 'Incl.' : ($supplierBill->vat_rate ? rtrim(rtrim($supplierBill->vat_rate,'0'),'.').'%' : '0%') }})</td><td class="text-right">{{ $supplierBill->currency?->code }} {{ number_format($supplierBill->tax_amount, 2) }}</td></tr>
                            @if((float)$supplierBill->discount > 0)
                            <tr><td>Discount</td><td class="text-right">- {{ $supplierBill->currency?->code }} {{ number_format($supplierBill->discount, 2) }}</td></tr>
                            @endif
                            <tr class="font-weight-bold"><td>Total</td><td class="text-right">{{ $supplierBill->currency?->code }} {{ number_format($supplierBill->total, 2) }}</td></tr>
                            <tr><td>Paid</td><td class="text-right text-success">{{ $supplierBill->currency?->code }} {{ number_format($supplierBill->paid_amount, 2) }}</td></tr>
                            <tr class="text-danger font-weight-bold"><td>Balance</td><td class="text-right">{{ $supplierBill->currency?->code }} {{ number_format($supplierBill->balance, 2) }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-outline card-info">
            <div class="card-header"><h5 class="card-title mb-0"><i class="fas fa-hand-holding-usd mr-1"></i> Payments</h5></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Payment</th><th>Date</th><th class="text-right">Amount</th></tr></thead>
                    <tbody>
                    @forelse($supplierBill->payments as $payment)
                        <tr>
                            <td><a href="{{ route('payments.show', $payment) }}">{{ $payment->number }}</a></td>
                            <td>{{ $payment->paid_on?->format('d M Y') }}</td>
                            <td class="text-right">{{ $payment->currency?->code }} {{ number_format($payment->pivot->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">No payments recorded.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @include('documents.partials._upload', ['morphType' => 'supplier_bill', 'morphClass' => 'App\\Models\\SupplierBill', 'entity' => $supplierBill, 'documents' => $supplierBill->documents])
    </div>
</div>
@endsection
