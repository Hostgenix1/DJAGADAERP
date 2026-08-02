@extends('layouts.app')

@section('title', 'New Follow-up')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">New Follow-up</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('follow_ups.store') }}">
                    @csrf

                    @include('follow_ups.partials.form', ['form' => null])
                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('follow_ups.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
