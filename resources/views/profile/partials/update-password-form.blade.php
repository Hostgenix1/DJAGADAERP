<div class="card" style="border:none; box-shadow: 0 0 15px rgba(0,0,0,.04); border-radius: 12px;">
    <div class="card-header border-0 pt-4 pb-2" style="border-bottom: 1px solid #f0f0f0 !important;">
        <h5 class="font-weight-bold text-dark mb-0" style="font-size:1.1rem;">
            <i class="fas fa-key mr-2 text-primary"></i>Update Password
        </h5>
    </div>
    <div class="card-body">
        <p class="text-muted">Ensure your account is using a long, random password to stay secure.</p>

        @if (session('status') === 'password-updated')
            <div class="alert alert-success">Saved.</div>
        @endif

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="form-group">
                <label for="update_password_current_password">Current Password</label>
                <input id="update_password_current_password" name="current_password" type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
                @error('current_password', 'updatePassword')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="update_password_password">New Password</label>
                <input id="update_password_password" name="password" type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
                @error('password', 'updatePassword')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="update_password_password_confirmation">Confirm Password</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
            </div>

            <div class="mt-4 pt-3" style="border-top: 1px solid #f0f0f0;">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save mr-1"></i> Save</button>
            </div>
        </form>
    </div>
</div>
