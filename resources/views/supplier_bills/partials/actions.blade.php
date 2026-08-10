<div class="d-flex align-items-center gap-1 flex-nowrap">
    <a href="{{ route('supplier_bills.show', $row->id) }}" class="btn btn-sm btn-outline-success" title="View"><i class="fas fa-eye"></i></a>
    @can('update-supplier-bills')
        @if($row->status === 'draft')
            <a href="{{ route('supplier_bills.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-pen"></i></a>
        @endif
    @endcan
    @can('delete-supplier-bills')
        @if(in_array($row->status, ['draft', 'cancelled']))
            <form method="POST" action="{{ route('supplier_bills.destroy', $row->id) }}" onsubmit="return confirm('Delete this supplier bill?')" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
            </form>
        @endif
    @endcan
</div>
