@extends('layouts.app')

@section('title', 'Edit Contact')

{{--
  Edit Contact - CRM Module
  Module: CRM
  Features: Contact edit form, pre-populated fields, PATCH method override, reusable form partial, validation errors
  Version: 1.0.0
--}}

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-address-book mr-1"></i> Edit Contact</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('contacts.update', $contact->id) }}">
                    @csrf
                    @method("PATCH")
                    @include('contacts.partials.form', ['form' => $contact])
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
