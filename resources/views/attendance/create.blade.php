@extends('layouts.app')

@section('title', 'Mark Attendance')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-calendar-check mr-1"></i> Mark Attendance</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('attendance.store') }}">
                    @csrf
                    @include('attendance.partials.form', ['form' => null])
                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('attendance.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection