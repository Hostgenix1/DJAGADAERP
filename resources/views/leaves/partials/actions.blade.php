<div class="d-flex align-items-center gap-1 flex-nowrap">
    @can('update-leaves')
        <a href="{{ route('leaves.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-pen"></i></a>
    @endcan
    @can('delete-leaves')
        <form method="POST" action="{{ route('leaves.destroy', $row) }}" class="d-inline" onsubmit="return confirm('Delete this leave request?');">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
        </form>
    @endcan
</div>