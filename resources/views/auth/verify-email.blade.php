@extends('layouts.guest')

@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">{{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}</p>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success">{{ __('A new verification link has been sent to the email address you provided during registration.') }}</div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-primary btn-block">{{ __('Resend Verification Email') }}</button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-default btn-block">{{ __('Log Out') }}</button>
        </form>
    </div>
</div>
@endsection