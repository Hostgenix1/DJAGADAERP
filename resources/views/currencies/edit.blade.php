@extends('layouts.app')

@section('title', 'Edit Currency')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">Edit Currency</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('currencies.update', $currency->id) }}">
                    @csrf
                    @method("PATCH")
                    @include('currencies.partials.form', ['form' => $currency])
                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('currencies.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
