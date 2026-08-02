<div class="d-flex align-items-center gap-1 flex-nowrap">
    <a href="{{ route('customers.show', $row->id) }}" class="btn btn-sm btn-outline-success" title="View"><i class="fas fa-eye"></i></a>
    @can('update-customers')
        <a href="{{ route('customers.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-pen"></i></a>
    @endcan
    @can('delete-customers')
        <form method="POST" action="{{ route('customers.destroy', $row->id) }}" onsubmit="return confirm('Delete this record?')" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
        </form>
    @endcan
</div>
