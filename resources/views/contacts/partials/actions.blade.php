<div class="btn-group" role="group">
    <a href="{{ route('contacts.edit', $row->id) }}" class="btn btn-xs btn-info" title="Edit"><i class="fas fa-pen"></i></a>
    <form method="POST" action="{{ route('contacts.destroy', $row->id) }}" onsubmit="return confirm('Delete this record?')" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-xs btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
    </form>
</div>
