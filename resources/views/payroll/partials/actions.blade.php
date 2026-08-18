<div class="d-flex align-items-center gap-1 flex-nowrap">
    @can('view-payroll')
        <a href="{{ route('payroll.payslip', $row->id) }}" class="btn btn-sm btn-outline-success" target="_blank" title="Payslip"><i class="fas fa-file-pdf"></i></a>
    @endcan
    @can('update-payroll')
        <a href="{{ route('payroll.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-pen"></i></a>
    @endcan
    @can('delete-payroll')
        <form method="POST" action="{{ route('payroll.destroy', $row) }}" class="d-inline" onsubmit="return confirm('Delete this payroll entry?');">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
        </form>
    @endcan
</div>