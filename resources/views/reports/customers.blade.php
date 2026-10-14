@extends('reports.layout')

@section('filters')
<form method="GET" action="{{ route('reports.customers') }}" class="form-inline mb-3">
    <label class="mr-1">From</label>
    <input type="date" name="from" class="form-control form-control-sm mr-2" value="{{ request('from', $meta['From'] ?? '') }}" data-allow-past="1">
    <label class="mr-1">To</label>
    <input type="date" name="to" class="form-control form-control-sm mr-2" value="{{ request('to', $meta['To'] ?? '') }}" data-allow-past="1">
    <button class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filter</button>
    <a href="{{ route('reports.customers') }}" class="btn btn-default btn-sm ml-1">Reset</a>
</form>
@endsection
