<div class="d-flex align-items-center gap-1 flex-nowrap">
    @can('update-supplier-prices')
        <a href="{{ route('supplier_prices.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-pen"></i></a>
    @endcan
    @can('delete-supplier-prices')
        <form method="POST" action="{{ route('supplier_prices.destroy', $row) }}" class="d-inline" onsubmit="return confirm('Delete this supplier price?');">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
        </form>
    @endcan
</div>