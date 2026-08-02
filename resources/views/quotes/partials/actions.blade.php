<div class="d-flex align-items-center gap-1 flex-nowrap">
    <a href="{{ route('quotes.show', $row->id) }}" class="btn btn-sm btn-outline-success" title="View">
        <i class="fas fa-eye"></i>
    </a>
    @if(in_array($row->status, ['draft', 'sent']))
        <a href="{{ route('quotes.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit">
            <i class="fas fa-pen"></i>
        </a>
    @endif
    @if($row->status === 'draft')
        <form method="POST" action="{{ route('quotes.convert', [$row, 'proforma']) }}" class="d-inline" onsubmit="return confirm('Convert to Proforma?');">
            @csrf
            <button class="btn btn-sm btn-outline-warning" title="Convert to Proforma"><i class="fas fa-exchange-alt"></i></button>
        </form>
    @endif
    @if(in_array($row->status, ['draft', 'sent', 'accepted']))
        <form method="POST" action="{{ route('quotes.convert', [$row, 'commercial']) }}" class="d-inline" onsubmit="return confirm('Convert to Invoice?');">
            @csrf
            <button class="btn btn-sm btn-outline-primary" title="Convert to Invoice"><i class="fas fa-file-invoice"></i></button>
        </form>
    @endif
    @if(in_array($row->status, ['draft', 'rejected']))
        <form method="POST" action="{{ route('quotes.destroy', $row) }}" class="d-inline" onsubmit="return confirm('Delete this quote?');">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
        </form>
    @endif
</div>
