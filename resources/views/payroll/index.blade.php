@extends('layouts.app')

@section('title', 'Payroll')

@section('content')
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $period }}</h3>
                <p>Selected Period</p>
            </div>
            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $defaultCurrency?->symbol ?? '$' }}{{ number_format($totalGross, 2) }}</h3>
                <p>Gross Salary</p>
            </div>
            <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $defaultCurrency?->symbol ?? '$' }}{{ number_format($totalNet, 2) }}</h3>
                <p>Net Payable</p>
            </div>
            <div class="icon"><i class="fas fa-hand-holding-usd"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if(($dueCount ?? 0) > 0)
            <div class="alert alert-warning"><i class="fas fa-bell mr-1"></i> {{ $dueCount }} payroll {{ $dueCount === 1 ? 'entry is' : 'entries are' }} <strong>DUE</strong> for {{ $period }} — mark as paid after bank transfer.</div>
        @endif
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-money-check-alt mr-1"></i> Payroll Entries</h3>
                <div class="card-tools">
                    <select id="period-select" class="form-control form-control-sm d-inline-block" style="width:auto">
                        @if($periods->isNotEmpty())
                            @foreach($periods as $p)
                                <option value="{{ $p }}" {{ $p === $period ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        @else
                            <option value="{{ $period }}">{{ $period }}</option>
                        @endif
                        <option value="{{ date('Y-m') }}">+ Current</option>
                    </select>
                    @can('create-payroll')
                        <a href="{{ route('payroll.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Payroll</a>
                    @endcan
                    <a href="{{ route('payroll.export', ['period' => $period]) }}" class="btn btn-success btn-sm" title="Bank salary-transfer file (CSV)"><i class="fas fa-file-csv"></i> Bank File</a>
                </div>
            </div>
            <div class="card-body border-bottom py-2">
                <div class="form-row align-items-end">
                    <div class="col-md-4 mb-1 mb-md-0">
                        <label class="mb-0 small text-muted">Employee</label>
                        <select id="filter-employee" class="form-control form-control-sm">
                            <option value="">All employees</option>
                            @foreach($employees as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-1 mb-md-0">
                        <label class="mb-0 small text-muted">Status</label>
                        <select id="filter-status" class="form-control form-control-sm">
                            <option value="">All statuses</option>
                            @foreach(['draft' => 'Draft', 'approved' => 'Approved', 'due' => 'Due', 'paid' => 'Paid'] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-1 mb-md-0">
                        <button type="button" id="filter-apply" class="btn btn-sm btn-primary"><i class="fas fa-filter mr-1"></i> Apply</button>
                        <button type="button" id="filter-reset" class="btn btn-sm btn-default">Reset</button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <table id="dt-payroll" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Employee</th>
                            <th>Period</th>
                            <th class="text-right">Gross</th>
                            <th class="text-right">Allowances</th>
                            <th class="text-right">Deductions</th>
                            <th class="text-right">Net</th>
                            <th>Currency</th>
                            <th>Status</th>
                            <th>Pay Date</th>
                            <th>Paid On</th>
                            <th style="width:130px">Actions</th>
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
    const table = $('#dt-payroll').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("payroll.datatable") }}',
            data: function (d) {
                d.period = $('#period-select').val();
                d.employee_id = $('#filter-employee').val();
                d.status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'employee_id', name: 'employee_id' },
            { data: 'period', name: 'period' },
            { data: 'gross_salary', name: 'gross_salary', className: 'text-right' },
            { data: 'allowances', name: 'allowances', className: 'text-right' },
            { data: 'deductions', name: 'deductions', className: 'text-right' },
            { data: 'net_salary', name: 'net_salary', className: 'text-right' },
            { data: 'currency_id', name: 'currency_id' },
            { data: 'status', name: 'status' },
            { data: 'pay_date', name: 'pay_date' },
            { data: 'paid_on', name: 'paid_on' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']]
    });

    $('#period-select').on('change', function () {
        window.location = '{{ route("payroll.index") }}?period=' + $(this).val();
    });

    $('#filter-employee, #filter-status').on('change', function () {
        table.ajax.reload();
    });

    $('#filter-apply').on('click', function () {
        table.ajax.reload();
    });

    $('#filter-reset').on('click', function () {
        $('#filter-employee, #filter-status').val('');
        table.ajax.reload();
    });
});
</script>
@endpush