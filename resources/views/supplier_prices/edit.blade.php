@extends('layouts.app')

@section('title', 'Edit Supplier Offer')

@section('content')
<div class="row">
    <div class="col-lg-10">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-truck-loading mr-1"></i> Edit Supplier Offer <span class="badge badge-dark ml-1">Internal / Confidential</span></h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('supplier_prices.update', $supplierPrice) }}">
                    @csrf
                    @method('PUT')
                    @include('supplier_prices.partials.form', ['form' => $supplierPrice])
                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                        <a href="{{ route('supplier_prices.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection