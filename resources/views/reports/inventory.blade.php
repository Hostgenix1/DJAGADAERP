@extends('reports.layout')

@section('filters')
<form method="GET" action="{{ route('reports.inventory') }}" class="form-inline mb-3">
    <a href="{{ route('reports.inventory') }}" class="btn btn-default btn-sm">Refresh</a>
    <a href="{{ route('stock.index') }}" class="btn btn-outline-primary btn-sm ml-1"><i class="fas fa-boxes"></i> Stock Levels</a>
</form>
@endsection
