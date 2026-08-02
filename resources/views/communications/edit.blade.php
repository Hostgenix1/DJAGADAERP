@extends('layouts.app')

@section('title', 'Edit Communication')

{{--
  Edit Communication - CRM Module
  Module: CRM
  Features: Communication log edit form, pre-populated fields, PATCH method override, reusable form partial, validation errors
  Version: 1.0.0
--}}

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">Edit Communication</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('communications.update', $communication->id) }}">
                    @csrf
                    @method("PATCH")
                    @include('communications.partials.form', ['form' => $communication])
                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('communications.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
