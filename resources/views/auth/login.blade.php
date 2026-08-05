@extends('layouts.guest')

{{--
  Login Page - Auth Module
  Module: Auth
  Features: Email/password login form, remember me checkbox, forgot password link, registration link, status message display, validation errors
  Version: 1.0.0
--}}

@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="input-group mb-3">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus autocomplete="username">
                <div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>
                @error('email')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="input-group mb-3">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
                <div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
                @error('password')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="row">
                <div class="col-8">
                    <div class="icheck-primary">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember Me</label>
                    </div>
                </div>
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                </div>
            </div>
        </form>

        <p class="mb-1 mt-3">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">I forgot my password</a>
            @endif
        </p>

    </div>
</div>
@endsection