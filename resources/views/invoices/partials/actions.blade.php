<div class="d-flex align-items-center gap-1 flex-nowrap">
    <a href="{{ route('invoices.show', $row->id) }}" class="btn btn-sm btn-outline-success" title="View">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ route('invoices.pdf', $row->id) }}" class="btn btn-sm btn-outline-danger" title="Download PDF">
        <i class="fas fa-file-pdf"></i>
    </a>
    @if(in_array($row->status, ['draft']))
        <a href="{{ route('invoices.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit">
            <i class="fas fa-pen"></i>
        </a>
    @endif
    @if(in_array($row->status, ['draft', 'cancelled']))
        <form method="POST" action="{{ route('invoices.destroy', $row) }}" class="d-inline" onsubmit="return confirm('Delete this invoice?');">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
        </form>
    @endif
</div>
