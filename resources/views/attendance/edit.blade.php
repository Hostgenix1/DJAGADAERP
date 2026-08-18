@extends('layouts.app')

@section('title', 'Edit Attendance')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-calendar-check mr-1"></i> Edit Attendance</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('attendance.update', $attendance) }}">
                    @csrf
                    @method('PUT')
                    @include('attendance.partials.form', ['form' => $attendance])
                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                        <a href="{{ route('attendance.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection