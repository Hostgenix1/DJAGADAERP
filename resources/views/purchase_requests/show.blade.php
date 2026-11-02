@extends('layouts.app')

@section('title', 'Purchase Request '.$purchaseRequest->number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="{{ route('purchase_requests.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Back to Requests</a>
    </div>
    <div class="d-flex flex-wrap">
        @can('update-purchase-requests')
            @if($purchaseRequest->status === 'draft')
                <a href="{{ route('purchase_requests.edit', $purchaseRequest) }}" class="btn btn-info btn-sm mr-2"><i class="fas fa-edit mr-1"></i> Edit</a>
                <form method="POST" action="{{ route('purchase_requests.submit', $purchaseRequest) }}" class="d-inline mr-2">
                    @csrf
                    <button class="btn btn-warning btn-sm" onclick="return confirm('Submit this request for approval?');"><i class="fas fa-paper-plane mr-1"></i> Submit for Approval</button>
                </form>
            @endif
        @endcan
        @can('approve-purchase-requests')
            @if($purchaseRequest->status === 'pending')
                <form method="POST" action="{{ route('purchase_requests.approve', $purchaseRequest) }}" class="d-inline mr-2">
                    @csrf
                    <button class="btn btn-success btn-sm" onclick="return confirm('Approve this request?');"><i class="fas fa-check mr-1"></i> Approve</button>
                </form>
                <button type="button" class="btn btn-danger btn-sm mr-2" data-toggle="modal" data-target="#reject-modal"><i class="fas fa-times mr-1"></i> Reject</button>
            @endif
        @endcan
        @can('create-purchase-orders')
            @if($purchaseRequest->status === 'approved')
                <form method="POST" action="{{ route('purchase_requests.convert', $purchaseRequest) }}" class="d-inline mr-2">
                    @csrf
                    <button class="btn btn-primary btn-sm" onclick="return confirm('Convert this request into a draft purchase order?');"><i class="fas fa-exchange-alt mr-1"></i> Convert to Purchase Order</button>
                </form>
            @endif
        @endcan
        @can('delete-purchase-requests')
            @if(in_array($purchaseRequest->status, ['draft', 'rejected']))
                <form method="POST" action="{{ route('purchase_requests.destroy', $purchaseRequest) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this request?');"><i class="fas fa-trash mr-1"></i> Delete</button>
                </form>
            @endif
        @endcan
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">{{ $purchaseRequest->number }}</h3>
                <div class="card-tools">
                    <span class="badge {{ $purchaseRequest->status_badge }} mr-2">{{ ucfirst($purchaseRequest->status) }}</span>
                    <span class="badge badge-light">{{ ucfirst($purchaseRequest->priority) }} priority</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Requester:</strong> {{ $purchaseRequest->requester?->name ?? '-' }}<br>
                        <strong>Department:</strong> {{ $purchaseRequest->department ?? '-' }}<br>
                        <strong>Needed By:</strong> {{ $purchaseRequest->needed_by?->format('d M Y') ?? '-' }}<br>
                        <strong>Supplier:</strong> {{ $purchaseRequest->supplier?->company_name ?? '-' }}
                    </div>
                    <div class="col-md-6 text-right">
                        <strong>Currency:</strong> {{ $purchaseRequest->currency?->code ?? 'Default' }}<br>
                        <strong>Created:</strong> {{ $purchaseRequest->created_at?->format('d M Y H:i') }}<br>
                        @if($purchaseRequest->approved_at)
                            <strong>Approved:</strong> {{ $purchaseRequest->approved_at->format('d M Y H:i') }}<br>
                            <strong>Approved By:</strong> {{ $purchaseRequest->approver?->name ?? '-' }}
                        @endif
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead><tr><th>Description</th><th class="text-right">Qty</th><th>Unit</th><th class="text-right">Unit Price</th><th class="text-right">Line Total</th></tr></thead>
                    <tbody>
                    @forelse($purchaseRequest->items as $item)
                        <tr>
                            <td>{{ $item->description }}</td>
                            <td class="text-right">{{ number_format((float) $item->qty, 2) }}</td>
                            <td>{{ $item->unit }}</td>
                            <td class="text-right">{{ number_format((float) $item->unit_price, 2) }}</td>
                            <td class="text-right">{{ number_format((float) $item->line_total, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">No items.</td></tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="table-primary">
                            <th colspan="4" class="text-right">Estimated Total</th>
                            <th class="text-right">{{ $purchaseRequest->currency?->code }} {{ number_format((float) $purchaseRequest->estimated_total, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>

                @if($purchaseRequest->justification)
                    <div class="mt-3"><strong>Justification:</strong><br>{!! nl2br(e($purchaseRequest->justification)) !!}</div>
                @endif
                @if($purchaseRequest->notes)
                    <div class="mt-3"><strong>Notes:</strong><br>{!! nl2br(e($purchaseRequest->notes)) !!}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Request Flow</h3></div>
            <div class="card-body">
                <ol class="pl-3 mb-3">
                    <li class="{{ in_array($purchaseRequest->status, ['draft', 'pending', 'approved', 'rejected', 'converted']) ? 'text-success' : '' }}">Draft</li>
                    <li class="{{ in_array($purchaseRequest->status, ['pending', 'approved', 'rejected', 'converted']) ? 'text-success' : 'text-muted' }}">Pending approval</li>
                    <li class="{{ in_array($purchaseRequest->status, ['approved', 'converted']) ? 'text-success' : ($purchaseRequest->status === 'rejected' ? 'text-danger' : 'text-muted') }}">
                        {{ $purchaseRequest->status === 'rejected' ? 'Rejected' : 'Approved' }}
                    </li>
                    <li class="{{ $purchaseRequest->status === 'converted' ? 'text-success' : 'text-muted' }}">Converted to PO</li>
                </ol>

                @if($purchaseRequest->purchaseOrder)
                    <a href="{{ route('purchase_orders.show', $purchaseRequest->purchaseOrder) }}" class="btn btn-outline-primary btn-block">
                        <i class="fas fa-clipboard-list mr-1"></i> View {{ $purchaseRequest->purchaseOrder->number }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

@can('approve-purchase-requests')
    @if($purchaseRequest->status === 'pending')
        <div class="modal fade" id="reject-modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('purchase_requests.reject', $purchaseRequest) }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Reject Purchase Request</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-0">
                                <label for="rejection_reason">Reason</label>
                                <textarea name="rejection_reason" id="rejection_reason" rows="3" class="form-control" maxlength="1000" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endcan
@endsection
