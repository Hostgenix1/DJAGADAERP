@extends('layouts.app')

@section('title', $employee->name)

@section('content')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">{{ $employee->name }}
            @if($employee->is_active)
                <span class="badge badge-success">Active</span>
            @else
                <span class="badge badge-secondary">Inactive</span>
            @endif
        </h1>
    </div>
    <div class="col-sm-6">
        <div class="float-right">
            @can('update-employees')
                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-info"><i class="fas fa-pen mr-1"></i> Edit</a>
            @endcan
            <a href="{{ route('employees.index') }}" class="btn btn-sm btn-default"><i class="fas fa-arrow-left mr-1"></i> Back</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">Employee Details</h3></div>
            <div class="card-body">
                <p class="mb-1"><strong>Position:</strong> {{ $employee->position ?: '—' }}</p>
                <p class="mb-1"><strong>Department:</strong> {{ $employee->department ?: '—' }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $employee->email ?: '—' }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $employee->phone ?: '—' }}</p>
                <p class="mb-1"><strong>Hire Date:</strong> {{ $employee->hire_date?->format('d M Y') ?? '—' }}</p>
                <p class="mb-1"><strong>Date of Birth:</strong> {{ $employee->date_of_birth?->format('d M Y') ?? '—' }}</p>
                <p class="mb-1"><strong>Nationality:</strong> {{ $employee->nationality ?: '—' }}</p>
                <p class="mb-1"><strong>Emirates ID:</strong> {{ $employee->emirates_id ?: '—' }}</p>
                <p class="mb-1"><strong>Labour Card / Person Code:</strong> {{ $employee->labour_card_no ?: '—' }}</p>
                <p class="mb-1"><strong>Portal Login:</strong> {{ $employee->user?->name ?? 'Not linked' }}</p>
            </div>
        </div>
        <div class="card card-outline card-success">
            <div class="card-header"><h3 class="card-title">Salary &amp; Bank</h3></div>
            <div class="card-body">
                <p class="mb-1"><strong>Base Salary:</strong> {{ $employee->currency?->code }} {{ number_format((float) $employee->base_salary, 2) }}</p>
                <p class="mb-1"><strong>Transport Allowance:</strong> {{ $employee->currency?->code }} {{ number_format((float) $employee->transport_allowance, 2) }}</p>
                <p class="mb-1"><strong>Bank:</strong> {{ $employee->bank_name ?: '—' }}</p>
                <p class="mb-1"><strong>IBAN:</strong> {{ $employee->iban ?: '—' }}</p>
                <p class="mb-1"><strong>Total Net Paid (history):</strong> {{ $employee->currency?->code }} {{ number_format((float) ($employee->total_net_paid ?? 0), 2) }}</p>
                <p class="mb-0"><strong>Attendance this month:</strong> {{ $attendanceThisMonth }} day(s) present</p>
            </div>
        </div>

        <div class="card card-outline card-warning">
            <div class="card-header"><h3 class="card-title">Recent Leaves</h3></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Type</th><th>From</th><th>To</th><th class="text-right">Days</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($leaves as $leave)
                        <tr>
                            <td>{{ ucfirst($leave->type) }}</td>
                            <td>{{ $leave->start_date?->format('d M Y') }}</td>
                            <td>{{ $leave->end_date?->format('d M Y') }}</td>
                            <td class="text-right">{{ rtrim(rtrim(number_format((float) $leave->days, 2), '0'), '.') }}</td>
                            <td><span class="badge {{ $leave->status === 'approved' ? 'badge-success' : ($leave->status === 'rejected' ? 'badge-danger' : 'badge-warning') }}">{{ ucfirst($leave->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">No leave records.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-outline card-info">
            <div class="card-header"><h3 class="card-title">Payroll History</h3></div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead><tr><th>Period</th><th class="text-right">Base</th><th class="text-right">Allowances</th><th class="text-right">Deductions</th><th class="text-right">Net</th><th>Status</th><th>Paid On</th><th></th></tr></thead>
                    <tbody>
                    @forelse($employee->payrollEntries as $entry)
                        <tr>
                            <td><strong>{{ $entry->period }}</strong></td>
                            <td class="text-right">{{ number_format((float) $entry->gross_salary, 2) }}</td>
                            <td class="text-right">{{ number_format((float) $entry->allowances, 2) }}</td>
                            <td class="text-right">{{ number_format((float) $entry->deductions, 2) }}</td>
                            <td class="text-right"><strong>{{ number_format((float) $entry->net_salary, 2) }}</strong></td>
                            <td><span class="badge {{ $entry->status === 'paid' ? 'badge-success' : ($entry->status === 'due' ? 'badge-warning' : 'badge-secondary') }}">{{ ucfirst($entry->status) }}</span></td>
                            <td>{{ $entry->paid_on?->format('d M Y') ?? '—' }}</td>
                            <td class="text-right">
                                @can('view-payroll')
                                    <a href="{{ route('payroll.payslip', $entry->id) }}" class="btn btn-xs btn-outline-success" target="_blank" title="Payslip PDF"><i class="fas fa-file-pdf"></i></a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted">No payroll records yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @include('documents.partials._upload', ['morphType' => 'employee', 'morphClass' => 'App\\Models\\Employee', 'entity' => $employee, 'documents' => $employee->documents])
    </div>
</div>
@endsection
