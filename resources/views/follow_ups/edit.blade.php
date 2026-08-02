@extends('layouts.app')

@section('title', 'Edit Follow-up')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">Edit Follow-up</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('follow_ups.update', $follow_up->id) }}">
                    @csrf
                    @method("PATCH")
                    @include('follow_ups.partials.form', ['form' => $follow_up])
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
