<div class="card" style="border:none; box-shadow: 0 0 15px rgba(0,0,0,.04); border-radius: 12px;">
    <div class="card-header border-0 pt-4 pb-2" style="border-bottom: 1px solid #f0f0f0 !important;">
        <h5 class="font-weight-bold text-dark mb-0" style="font-size:1.1rem;">
            <i class="fas fa-user mr-2 text-primary"></i>Profile Information
        </h5>
    </div>
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

            <div class="mt-4 pt-3" style="border-top: 1px solid #f0f0f0;">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save mr-1"></i> Save</button>
            </div>
        </form>
    </div>
</div>

@if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">@csrf</form>
@endif
