<div class="d-flex align-items-center gap-1 flex-nowrap">
    @if(in_array($row->status, ['approved', 'due'], true))
        @can('update-payroll')
            <form method="POST" action="{{ route('payroll.mark-paid', $row->id) }}" class="d-inline" onsubmit="return confirm('Mark as PAID? Payslip will be emailed to the employee.');">
                @csrf
                <button class="btn btn-sm btn-outline-success" title="Mark as Paid + email payslip"><i class="fas fa-check"></i></button>
            </form>
        @endcan
    @endif
    @can('view-payroll')
        <a href="{{ route('payroll.payslip', $row->id) }}" class="btn btn-sm btn-outline-success" target="_blank" title="Payslip"><i class="fas fa-file-pdf"></i></a>
    @endcan
    @can('update-payroll')
        @if($row->status !== 'paid')
            <a href="{{ route('payroll.edit', $row->id) }}" class="btn btn-sm btn-outline-info" title="Edit"><i class="fas fa-pen"></i></a>
        @endif
    @endcan
    @can('delete-payroll')
        @if($row->status !== 'paid')
            <form method="POST" action="{{ route('payroll.destroy', $row) }}" class="d-inline" onsubmit="return confirm('Delete this payroll entry?');">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
            </form>
        @endif
    @endcan
</div>
