<div class="card" style="border:none; box-shadow: 0 0 15px rgba(0,0,0,.04); border-radius: 12px;">
    <div class="card-header border-0 pt-4 pb-2" style="border-bottom: 1px solid #f0f0f0 !important;">
        <h5 class="font-weight-bold text-dark mb-0" style="font-size:1.1rem;">
            <i class="fas fa-trash mr-2 text-danger"></i>Delete Account
        </h5>
    </div>
    <div class="card-body">
        <p class="text-muted">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>

        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteAccountModal">Delete Account</button>

        <div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="post" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')
                        <div class="modal-header">
                            <h5 class="modal-title">Are you sure you want to delete your account?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <p>Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
                            <div class="form-group">
                                <input id="password" name="password" type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" placeholder="Password">
                                @error('password', 'userDeletion')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
