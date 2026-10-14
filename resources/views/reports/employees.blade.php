@extends('reports.layout')

@section('filters')
<form method="GET" action="{{ route('reports.employees') }}" class="form-inline mb-3">
    <label class="mr-1">Period</label>
    <input type="month" name="period" class="form-control form-control-sm mr-2" value="{{ $meta['Period'] ?? now()->format('Y-m') }}" data-allow-past="1">
    <button class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Filter</button>
    <a href="{{ route('reports.employees') }}" class="btn btn-default btn-sm ml-1">Reset</a>
</form>
@endsection
