@extends('layouts.app')

@section('title', 'New Customer')

{{--
  Create Customer - CRM Module
  Module: CRM
  Features: Customer creation form, reusable form partial, validation error display, save/cancel actions
  Version: 1.0.0
--}}

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">New Customer</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('customers.store') }}">
                    @csrf

                    @include('customers.partials.form', ['form' => null])
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
