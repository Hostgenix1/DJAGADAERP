@extends('layouts.guest')

@section('content')
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">You forgot your password? Enter your email to request a reset link.</p>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="input-group mb-3">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                <div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>
                @error('email')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Email Password Reset Link</button>
                </div>
            </div>
        </form>
        <p class="mt-3 mb-1"><a href="{{ route('login') }}">Back to login</a></p>
    </div>
</div>
@endsection