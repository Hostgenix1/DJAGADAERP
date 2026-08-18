@extends('layouts.app')

@section('title', 'Employees')

@section('content')
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $total }}</h3>
                <p>Total Employees</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $active }}</h3>
                <p>Active Employees</p>
            </div>
            <div class="icon"><i class="fas fa-user-check"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $defaultCurrency?->symbol ?? '$' }}{{ number_format($monthlyPayroll, 2) }}</h3>
                <p>Monthly Salary Budget</p>
            </div>
            <div class="icon"><i class="fas fa-money-check-alt"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-tie mr-1"></i> Employees</h3>
                <div class="card-tools">
                    @can('create-employees')
                        <a href="{{ route('employees.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Employee</a>
                    @endcan
                </div>
            </div>
            <div class="card-body p-0">
                <table id="dt-employees" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Position</th>
                            <th>Hire Date</th>
                            <th class="text-right">Base Salary</th>
                            <th>Currency</th>
                            <th>Status</th>
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
    $('#dt-employees').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("employees.datatable") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'department', name: 'department' },
            { data: 'position', name: 'position' },
            { data: 'hire_date', name: 'hire_date' },
            { data: 'base_salary', name: 'base_salary', className: 'text-right' },
            { data: 'currency_id', name: 'currency_id' },
            { data: 'is_active', name: 'is_active' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']]
    });
});
</script>
@endpush