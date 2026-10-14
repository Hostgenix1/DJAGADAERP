@extends('reports.layout')

@section('filters')
<form method="GET" action="{{ route('reports.sales') }}" class="form-inline mb-3">
    <label class="mr-1">From</label>
    <input type="date" name="from" class="form-control form-control-sm mr-2" value="{{ request('from', $meta['From'] ?? '') }}" data-allow-past="1">
    <label class="mr-1">To</label>
    <input type="date" name="to" class="form-control form-control-sm mr-2" value="{{ request('to', $meta['To'] ?? '') }}" data-allow-past="1">
    <select name="customer_id" class="form-control form-control-sm mr-2">
        <option value="">All Customers</option>
        @foreach(\App\Models\Customer::orderBy('company_name')->pluck('company_name', 'id') as $id => $name)
            <option value="{{ $id }}" {{ request('customer_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
        @endforeach
    </select>
    <button class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filter</button>
    <a href="{{ route('reports.sales') }}" class="btn btn-default btn-sm ml-1">Reset</a>
</form>
@endsection
