@extends('layouts.app')

@section('title', 'My Profile')

{{--
  User Profile Settings - Profile Module
  Module: Profile
  Features: Update profile information form, update password form, delete account form, two-column layout
  Version: 1.0.0
--}}

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
