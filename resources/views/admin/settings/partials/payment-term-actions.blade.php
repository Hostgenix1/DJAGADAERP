@can('update-settings')
    <a href="#" class="btn btn-sm btn-info js-edit-term" data-id="{{ $row->id }}"
       data-name="{{ $row->name }}" data-sort="{{ $row->sort_order }}"
       data-default="{{ $row->is_default ? 1 : 0 }}" data-active="{{ $row->is_active ? 1 : 0 }}" title="Edit"><i class="fas fa-edit"></i></a>
    <form action="{{ route('admin.settings.payment_terms.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this payment term?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
    </form>
@endcan
