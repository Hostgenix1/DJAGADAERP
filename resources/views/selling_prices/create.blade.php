@extends('layouts.app')

@section('title', 'New Selling Price')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-dollar-sign mr-1"></i> New Selling Price</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('selling_prices.store') }}">
                    @csrf
                    @include('selling_prices.partials.form', ['form' => null])
                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('selling_prices.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection