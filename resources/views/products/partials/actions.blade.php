<div class="d-flex align-items-center gap-1 flex-nowrap">
    <a href="{{ route('products.show', $row->id) }}" class="btn btn-sm btn-outline-success" title="View"><i class="fas fa-eye"></i></a>
    @can('update-products')
        <a href="{{ route('products.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-pen"></i></a>
        <form method="POST" action="{{ route('products.toggle-active', $row->id) }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm {{ $row->is_active ? 'btn-success' : 'btn-secondary' }}"
                    title="{{ $row->is_active ? 'Enabled — click to disable' : 'Disabled — click to enable' }}">
                <i class="fas {{ $row->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
            </button>
        </form>
    @endcan
    @can('delete-products')
        <form method="POST" action="{{ route('products.destroy', $row->id) }}" onsubmit="return confirm('Delete this record?')" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
        </form>
    @endcan
</div>
