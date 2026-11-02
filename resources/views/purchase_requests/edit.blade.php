@extends('layouts.app')

@section('title', 'Edit Purchase Request '.$purchaseRequest->number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="fas fa-edit mr-1"></i> Edit {{ $purchaseRequest->number }}</h4>
    <a href="{{ route('purchase_requests.show', $purchaseRequest) }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i> Back</a>
</div>

@include('purchase_requests.partials.form', ['purchaseRequest' => $purchaseRequest])
@endsection
