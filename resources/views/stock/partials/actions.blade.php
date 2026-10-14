<div class="d-flex align-items-center gap-1 flex-nowrap">
    @can('delete-stock-movements')
        <form method="POST" action="{{ route('stock.movements.destroy', $row) }}" class="d-inline" onsubmit="return confirm('Delete this movement?');">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
        </form>
    @endcan
</div>