@extends('layouts.app')

@section('title', 'New Expense')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-receipt mr-1"></i> New Expense</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('expenses.store') }}">
                    @csrf
                    @include('expenses.partials.form', ['form' => null])
                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('expenses.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection