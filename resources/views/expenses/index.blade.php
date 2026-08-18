@extends('layouts.app')

@section('title', 'Expenses')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $defaultCurrency?->symbol ?? '$' }}{{ number_format($totalOperating, 2) }}</h3>
                <p>Operating Expenses</p>
            </div>
            <div class="icon"><i class="fas fa-tools"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-dark">
            <div class="inner">
                <h3>{{ $defaultCurrency?->symbol ?? '$' }}{{ number_format($totalPayroll, 2) }}</h3>
                <p>Payroll Expenses</p>
            </div>
            <div class="icon"><i class="fas fa-user-tie"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $defaultCurrency?->symbol ?? '$' }}{{ number_format($totalAll, 2) }}</h3>
                <p>Total Expenses</p>
            </div>
            <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-receipt mr-1"></i> Expenses</h3>
                <div class="card-tools">
                    @can('create-expenses')
                        <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> New Expense
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-pills px-3 pt-3">
                    <li class="nav-item"><a class="nav-link active filter-tab" href="#" data-category="">All</a></li>
                    <li class="nav-item"><a class="nav-link filter-tab" href="#" data-category="operating">Operating</a></li>
                    <li class="nav-item"><a class="nav-link filter-tab" href="#" data-category="payroll">Payroll</a></li>
                    <li class="nav-item"><a class="nav-link filter-tab" href="#" data-category="transport">Transport</a></li>
                    <li class="nav-item"><a class="nav-link filter-tab" href="#" data-category="rent">Rent</a></li>
                    <li class="nav-item"><a class="nav-link filter-tab" href="#" data-category="bank">Bank</a></li>
                    <li class="nav-item"><a class="nav-link filter-tab" href="#" data-category="office">Office</a></li>
                    <li class="nav-item"><a class="nav-link filter-tab" href="#" data-category="other">Other</a></li>
                </ul>
            </div>
            <div class="card-body p-0">
                <table id="dt-expenses" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Paid To</th>
                            <th class="text-right">Amount</th>
                            <th>Currency</th>
                            <th style="width:120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
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
$(function () {
    let activeCat = '';
    const table = $('#dt-expenses').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("expenses.datatable") }}',
            data: function (d) { d.category = activeCat; }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'expense_date', name: 'expense_date' },
            { data: 'category', name: 'category' },
            { data: 'description', name: 'description' },
            { data: 'paid_to', name: 'paid_to' },
            { data: 'amount', name: 'amount', className: 'text-right' },
            { data: 'currency_id', name: 'currency_id' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']]
    });

    $(document).on('click', '.filter-tab', function (e) {
        e.preventDefault();
        activeCat = $(this).data('category');
        $('.filter-tab').removeClass('active');
        $(this).addClass('active');
        table.ajax.reload();
    });
});
</script>
@endpush