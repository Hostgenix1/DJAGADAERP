@extends('layouts.app')

@section('title', 'Edit Lead')

{{--
  Edit Lead - CRM Module
  Module: CRM
  Features: Lead edit form, pre-populated fields, PATCH method override, reusable form partial, validation errors
  Version: 1.0.0
--}}

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-funnel-dollar mr-1"></i> Edit Lead</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('leads.update', $lead->id) }}">
                    @csrf
                    @method("PATCH")
                    @include('leads.partials.form', ['form' => $lead])
                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('leads.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
