<div class="d-flex align-items-center gap-1 flex-nowrap">
    <a href="{{ route('purchase_orders.show', $row->id) }}" class="btn btn-sm btn-outline-success" title="View"><i class="fas fa-eye"></i></a>
    @can('update-purchase-orders')
        @if($row->status === 'draft')
            <a href="{{ route('purchase_orders.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-pen"></i></a>
        @endif
    @endcan
    @can('create-supplier-bills')
        @if($row->status !== 'billed' && $row->status !== 'cancelled')
            <form method="POST" action="{{ route('supplier_bills.convert', $row->id) }}" class="d-inline" onsubmit="return confirm('Create Supplier Bill from this PO?');">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-success" title="Create Supplier Bill"><i class="fas fa-file-invoice-dollar"></i></button>
            </form>
        @endif
    @endcan
    @can('delete-purchase-orders')
        @if(in_array($row->status, ['draft', 'cancelled']))
            <form method="POST" action="{{ route('purchase_orders.destroy', $row->id) }}" onsubmit="return confirm('Delete this purchase order?')" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
            </form>
        @endif
    @endcan
</div>
