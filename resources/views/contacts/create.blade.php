@extends('layouts.app')

@section('title', 'New Contact')

{{--
  Create Contact - CRM Module
  Module: CRM
  Features: Contact creation form, reusable form partial, linked to customer, validation, save/cancel actions
  Version: 1.0.0
--}}

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-address-book mr-1"></i> New Contact</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('contacts.store') }}">
                    @csrf

                    @include('contacts.partials.form', ['form' => null])
                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('contacts.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
