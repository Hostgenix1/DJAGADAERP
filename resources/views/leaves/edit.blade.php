@extends('layouts.app')

@section('title', 'Edit Leave')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-umbrella-beach mr-1"></i> Edit Leave Request</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('leaves.update', $leave) }}">
                    @csrf
                    @method('PUT')
                    @include('leaves.partials.form', ['form' => $leave])
                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                        <a href="{{ route('leaves.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection