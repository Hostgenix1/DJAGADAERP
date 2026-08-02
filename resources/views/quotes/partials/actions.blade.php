<div class="btn-group" role="group">
    <a href="{{ route('quotes.show', $row->id) }}" class="btn btn-xs btn-success" title="View"><i class="fas fa-eye"></i></a>
    @if(in_array($row->status, ['draft', 'sent']))
        <a href="{{ route('quotes.edit', $row->id) }}" class="btn btn-xs btn-info" title="Edit"><i class="fas fa-pen"></i></a>
    @endif
    @if($row->status === 'draft')
        <form method="POST" action="{{ route('quotes.convert', [$row, 'proforma']) }}" class="d-inline" onsubmit="return confirm('Convert to Proforma?');">
            @csrf
            <button class="btn btn-xs btn-warning" title="To Proforma"><i class="fas fa-exchange-alt"></i></button>
        </form>
    @endif
    @if(in_array($row->status, ['draft', 'sent', 'accepted']))
        <form method="POST" action="{{ route('quotes.convert', [$row, 'commercial']) }}" class="d-inline" onsubmit="return confirm('Convert to Invoice?');">
            @csrf
            <button class="btn btn-xs btn-primary" title="To Invoice"><i class="fas fa-file-invoice"></i></button>
        </form>
    @endif
    @if(in_array($row->status, ['draft', 'rejected']))
        <form method="POST" action="{{ route('quotes.destroy', $row) }}" class="d-inline" onsubmit="return confirm('Delete?');">
            @csrf @method('DELETE')
            <button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
        </form>
    @endif
</div>
