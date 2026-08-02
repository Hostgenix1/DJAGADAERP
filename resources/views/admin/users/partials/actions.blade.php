@can('update-users')
    <a href="{{ route('admin.users.edit', $row->id) }}" class="btn btn-sm btn-info" title="Edit"><i class="fas fa-edit"></i></a>
@endcan
@can('delete-users')
    @if ($row->id !== auth()->id())
        <form action="{{ route('admin.users.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
        </form>
    @endif
@endcan