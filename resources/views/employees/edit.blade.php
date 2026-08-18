@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-tie mr-1"></i> Edit Employee</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('employees.update', $employee) }}">
                    @csrf
                    @method('PUT')
                    @include('employees.partials.form', ['form' => $employee])
                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                        <a href="{{ route('employees.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection