@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            @include('profile.partials.update-profile-information-form')
        </div>
        <div class="col-lg-6">
            @include('profile.partials.update-password-form')
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
@endsection