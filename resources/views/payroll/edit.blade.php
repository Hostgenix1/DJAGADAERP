@extends('layouts.app')

@section('title', 'Edit Payroll Entry')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-money-check-alt mr-1"></i> Edit Payroll Entry</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('payroll.update', $payrollEntry) }}" id="payroll-form">
                    @csrf
                    @method('PUT')
                    @include('payroll.partials.form', ['form' => $payrollEntry])
                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                        <a href="{{ route('payroll.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection