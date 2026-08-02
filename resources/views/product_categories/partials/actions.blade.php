<div class="d-flex align-items-center gap-1 flex-nowrap">
    @can('update-product_categories')
        <a href="{{ route('product_categories.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-pen"></i></a>
    @endcan
    @can('delete-product_categories')
        <form method="POST" action="{{ route('product_categories.destroy', $row->id) }}" onsubmit="return confirm('Delete this record?')" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
        </form>
    @endcan
</div>
