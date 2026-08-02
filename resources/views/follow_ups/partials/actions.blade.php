<div class="d-flex align-items-center gap-1 flex-nowrap">
    @can('update-follow-ups')
        <a href="{{ route('follow_ups.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-pen"></i></a>
    @endcan
    @can('delete-follow-ups')
        <form method="POST" action="{{ route('follow_ups.destroy', $row->id) }}" onsubmit="return confirm('Delete this record?')" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
        </form>
    @endcan
</div>
