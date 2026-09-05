<div class="d-flex align-items-center gap-1 flex-nowrap">
    @if($row->status === 'draft')
        @can('approve-selling-prices')
            <form method="POST" action="{{ route('selling_prices.approve', $row->id) }}" class="d-inline" onsubmit="return confirm('Approve this selling price?');">
                @csrf
                <button class="btn btn-sm btn-outline-success" title="Approve"><i class="fas fa-check"></i></button>
            </form>
        @endcan
    @endif
    @can('update-selling-prices')
        <a href="{{ route('selling_prices.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-pen"></i></a>
    @endcan
    @can('delete-selling-prices')
        <form method="POST" action="{{ route('selling_prices.destroy', $row) }}" class="d-inline" onsubmit="return confirm('Delete this selling price?');">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
        </form>
    @endcan
</div>