<div class="d-flex align-items-center gap-1 flex-nowrap">
    @can('update-currencies')
        <a href="{{ route('currencies.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-pen"></i></a>
    @endcan
    @can('delete-currencies')
        <form method="POST" action="{{ route('currencies.destroy', $row->id) }}" onsubmit="return confirm('Delete this record?')" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
        </form>
    @endcan
</div>
