@extends('layouts.app')

@section('title', 'My Payslips')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-pdf mr-1"></i> {{ ($isHrView ?? false) ? 'All Payslips (HR View)' : 'My Payslips' }}
                    @if($employee)<small class="text-muted">{{ $employee->name }}</small>@endif
                </h3>
                @if(($isHrView ?? false) && !empty($employees))
                    <div class="card-tools">
                        <form method="GET" action="{{ route('my-payslips.index') }}" class="form-inline">
                            <select name="employee_id" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="">-- All Employees --</option>
                                @foreach($employees as $id => $nm)
                                    <option value="{{ $id }}" {{ request('employee_id') == $id ? 'selected' : '' }}>{{ $nm }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                @endif
            </div>
            <div class="card-body p-0">
                @if(!$employee && !($isHrView ?? false))
                    <div class="alert alert-warning m-3 mb-0">Your login is not linked to an employee profile yet. Please contact HR.</div>
                @else
                    <table class="table table-bordered table-hover mb-0">
                        <thead>
                            <tr>
                                @if(($isHrView ?? false))<th>Employee</th>@endif
                                <th>Period</th>
                                <th class="text-right">Gross</th>
                                <th class="text-right">Allowances</th>
                                <th class="text-right">Deductions</th>
                                <th class="text-right">Net Pay</th>
                                <th>Status</th>
                                <th>Paid On</th>
                                <th style="width:120px">Payslip</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($entries as $e)
                                <tr>
                                    @if(($isHrView ?? false))<td>{{ $e->employee?->name ?? '—' }}</td>@endif
                                    <td><strong>{{ $e->period }}</strong></td>
                                    <td class="text-right">{{ number_format((float) $e->gross_salary, 2) }}</td>
                                    <td class="text-right">{{ number_format((float) $e->allowances, 2) }}</td>
                                    <td class="text-right">{{ number_format((float) $e->deductions, 2) }}</td>
                                    <td class="text-right"><strong>{{ $e->currency?->code ?? '' }} {{ number_format((float) $e->net_salary, 2) }}</strong></td>
                                    <td><span class="badge {{ $e->status === 'paid' ? 'badge-success' : ($e->status === 'due' ? 'badge-warning' : 'badge-secondary') }}">{{ ucfirst($e->status) }}</span></td>
                                    <td>{{ $e->paid_on?->format('d M Y') ?? '—' }}</td>
                                    <td>
                                        @if(in_array($e->status, ['due', 'paid'], true))
                                            <a href="{{ route('my-payslips.download', $e->id) }}" class="btn btn-sm btn-outline-success"><i class="fas fa-download"></i> PDF</a>
                                        @else
                                            <span class="text-muted" title="Available after HR finalises">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="{{ ($isHrView ?? false) ? 9 : 8 }}" class="text-center text-muted">No salary records yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
