@extends('layouts.app')

@section('title', 'New Payroll Entry')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-money-check-alt mr-1"></i> New Payroll Entry</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('payroll.store') }}" id="payroll-form">
                    @csrf
                    @include('payroll.partials.form', ['form' => null])
                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('payroll.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection