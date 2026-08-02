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
        <div class="card" style="border:none; box-shadow: 0 0 15px rgba(0,0,0,.04); border-radius: 12px;">
            <div class="card-header border-0 pt-4 pb-2" style="border-bottom: 1px solid #f0f0f0 !important;">
                <h5 class="font-weight-bold text-dark mb-0" style="font-size:1.1rem;">
                    <i class="fas fa-address-book mr-2 text-primary"></i>New Contact
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('contacts.store') }}">
                    @csrf

                    @include('contacts.partials.form', ['form' => null])
                    <div class="mt-4 pt-3" style="border-top: 1px solid #f0f0f0;">
                        <button class="btn btn-primary btn-sm"><i class="fas fa-save mr-1"></i> Save</button>
                        <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary btn-sm ml-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
