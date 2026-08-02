<div class="d-flex align-items-center gap-1 flex-nowrap">
    <a href="{{ route('shipments.show', $row->id) }}" class="btn btn-sm btn-outline-success" title="View">
        <i class="fas fa-eye"></i>
    </a>
    @if(in_array($row->status, ['preparing', 'in_transit', 'customs']))
        @can('update-shipments')
            <a href="{{ route('shipments.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit">
                <i class="fas fa-pen"></i>
            </a>
        @endcan
    @endif
    @if(in_array($row->status, ['preparing']))
        @can('delete-shipments')
            <form method="POST" action="{{ route('shipments.destroy', $row) }}" class="d-inline" onsubmit="return confirm('Delete this shipment?');">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
            </form>
        @endcan
    @endif
</div>
