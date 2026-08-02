<div class="d-flex align-items-center gap-1 flex-nowrap">
    <a href="{{ route('products.show', $row->id) }}" class="btn btn-sm btn-outline-success" title="View"><i class="fas fa-eye"></i></a>
    @can('update-products')
        <a href="{{ route('products.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-pen"></i></a>
    @endcan
    @can('delete-products')
        <form method="POST" action="{{ route('products.destroy', $row->id) }}" onsubmit="return confirm('Delete this record?')" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
        </form>
    @endcan
</div>
