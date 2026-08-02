<div class="card card-primary card-outline">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-user mr-1"></i> Profile Information</h3></div>
    <div class="card-body">
        <p class="text-muted">Update your account's profile information and email address.</p>

        @if (session('status') === 'verification-link-sent')
            <div class="alert alert-info">A new verification link has been sent to your email address.</div>
        @endif
        @if (session('status') === 'profile-updated')
            <div class="alert alert-success">Saved.</div>
        @endif

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="form-group">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autocomplete="name">
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <small class="form-text text-muted">
                        Your email address is unverified.
                        <a href="{{ route('verification.send') }}" onclick="event.preventDefault(); document.getElementById('send-verification').submit();">Click here to re-send the verification email.</a>
                    </small>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>

@if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">@csrf</form>
@endif