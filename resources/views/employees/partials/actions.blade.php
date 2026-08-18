<div class="d-flex align-items-center gap-1 flex-nowrap">
    @can('update-employees')
        <a href="{{ route('employees.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-pen"></i></a>
    @endcan
    @can('delete-employees')
        <form method="POST" action="{{ route('employees.destroy', $row) }}" class="d-inline" onsubmit="return confirm('Delete this employee?');">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
        </form>
    @endcan
</div>