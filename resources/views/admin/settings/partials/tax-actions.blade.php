@can('update-settings')
    <a href="#" class="btn btn-sm btn-info js-edit-tax" data-id="{{ $row->id }}"
       data-name="{{ $row->name }}" data-rate="{{ $row->rate }}" data-kind="{{ $row->kind }}" title="Edit"><i class="fas fa-edit"></i></a>
    <form action="{{ route('admin.settings.taxes.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this tax?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
    </form>
@endcan