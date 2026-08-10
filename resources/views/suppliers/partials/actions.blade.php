<div class="d-flex align-items-center gap-1 flex-nowrap">
    @can('view-suppliers')
        <a href="{{ route('suppliers.show', $row->id) }}" class="btn btn-sm btn-outline-success" title="View History"><i class="fas fa-eye"></i></a>
    @endcan
    @can('view-supplier-bills')
        <a href="{{ route('supplier_bills.index', ['supplier_id' => $row->id]) }}" class="btn btn-sm btn-outline-primary" title="Bills"><i class="fas fa-file-invoice-dollar"></i></a>
    @endcan
    @can('update-suppliers')
        <a href="{{ route('suppliers.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-pen"></i></a>
    @endcan
    @can('delete-suppliers')
        <form method="POST" action="{{ route('suppliers.destroy', $row->id) }}" onsubmit="return confirm('Delete this record?')" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
        </form>
    @endcan
</div>
