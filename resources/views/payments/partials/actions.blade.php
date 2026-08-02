<div class="btn-group" role="group">
    <a href="{{ route('payments.show', $row->id) }}" class="btn btn-xs btn-success" title="View"><i class="fas fa-eye"></i></a>
    @if(in_array($row->type, ['customer']))
        <a href="{{ route('customers.show', $row->customer_id) }}" class="btn btn-xs btn-info" title="Customer"><i class="fas fa-building"></i></a>
    @else
        <a href="{{ route('suppliers.show', $row->supplier_id) }}" class="btn btn-xs btn-info" title="Supplier"><i class="fas fa-truck"></i></a>
    @endif
    @can('delete-payments')
        <form method="POST" action="{{ route('payments.destroy', $row) }}" class="d-inline" onsubmit="return confirm('Delete this payment?');">
            @csrf @method('DELETE')
            <button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
        </form>
    @endcan
</div>
