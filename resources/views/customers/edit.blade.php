@extends('layouts.app')

@section('title', 'Edit Customer')

{{--
  Edit Customer - CRM Module
  Module: CRM
  Features: Customer edit form, pre-populated fields, PATCH method override, reusable form partial, validation errors
  Version: 1.0.0
--}}

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">Edit Customer</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('customers.update', $customer->id) }}">
                    @csrf
                    @method("PATCH")
                    @include('customers.partials.form', ['form' => $customer])
                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('customers.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
