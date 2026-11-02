<div class="d-flex align-items-center gap-1 flex-nowrap">
    <a href="{{ route('purchase_requests.show', $row->id) }}" class="btn btn-sm btn-outline-success" title="View"><i class="fas fa-eye"></i></a>
    @can('update-purchase-requests')
        @if($row->status === 'draft')
            <a href="{{ route('purchase_requests.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-pen"></i></a>
        @endif
    @endcan
    @can('delete-purchase-requests')
        @if(in_array($row->status, ['draft', 'rejected']))
            <form method="POST" action="{{ route('purchase_requests.destroy', $row->id) }}" onsubmit="return confirm('Delete this purchase request?')" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
            </form>
        @endif
    @endcan
</div>
