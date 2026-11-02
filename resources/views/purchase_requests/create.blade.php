@extends('layouts.app')

@section('title', 'New Purchase Request')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="fas fa-clipboard-check mr-1"></i> New Purchase Request</h4>
    <a href="{{ route('purchase_requests.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Back</a>
</div>

@include('purchase_requests.partials.form')
@endsection
