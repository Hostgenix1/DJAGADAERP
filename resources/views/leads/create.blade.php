@extends('layouts.app')

@section('title', 'New Lead')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">New Lead</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('leads.store') }}">
                    @csrf

                    @include('leads.partials.form', ['form' => null])
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
