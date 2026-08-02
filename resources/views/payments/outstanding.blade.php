@extends('layouts.app')
@section('title', 'Outstanding Balances')
@section('content')
<div class="card card-warning card-outline">
    <div class="card-header"><h3 class="card-title">Outstanding Invoice Balances</h3></div>
    <div class="card-body">
        <table class="table table-bordered table-hover" id="dt-outstanding">
            <thead><tr><th>Invoice</th><th>Customer</th><th>Total</th><th>Paid</th><th>Balance</th><th>Due Date</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($invoices as $inv)
            <tr class="{{ $inv['overdue'] ? 'table-danger' : '' }}">
                <td><a href="{{ route('invoices.show', $inv['id']) }}">{{ $inv['number'] }}</a></td>
                <td>{{ $inv['customer'] }}</td>
                <td>{{ number_format($inv['total'], 2) }}</td>
                <td>{{ number_format($inv['paid'], 2) }}</td>
                <td><strong>{{ number_format($inv['balance'], 2) }}</strong></td>
                <td>{{ $inv['due_date'] }} @if($inv['overdue'])<span class="badge badge-danger">Overdue</span>@endif</td>
                <td><span class="badge badge-{{ $inv['balance']<=0 ? 'success' : ($inv['overdue'] ? 'danger' : 'warning') }}">{{ $inv['balance']<=0 ? 'Paid' : ($inv['overdue'] ? 'Overdue' : 'Pending') }}</span></td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted">All invoices are paid up.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function(){ $('#dt-outstanding').DataTable({order:[[4,'desc']]}); });
</script>
@endpush
