@extends('layouts.app')

@section('title', 'New User')

{{--
  Create User - Settings Module
  Module: Settings
  Features: User creation form, name/email/password fields, password confirmation, role checkbox assignment, validation, save/cancel actions
  Version: 1.0.0
--}}

@section('content')
<div class="row">
    <div class="col-lg-7">
        <div class="card" style="border:none; box-shadow: 0 0 15px rgba(0,0,0,.04); border-radius: 12px;">
            <div class="card-header border-0 pt-4 pb-2" style="border-bottom: 1px solid #f0f0f0 !important;">
                <h5 class="font-weight-bold text-dark mb-0" style="font-size:1.1rem;">
                    <i class="fas fa-user-plus mr-2 text-primary"></i>New User
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Roles</label>
                        @foreach($roles as $role)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="roles[]"
                                       value="{{ $role['id'] }}" id="role-{{ $role['id'] }}"
                                       {{ in_array($role['id'], (array) old('roles', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role-{{ $role['id'] }}">{{ $role['name'] }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-3" style="border-top: 1px solid #f0f0f0;">
                        <button class="btn btn-primary btn-sm"><i class="fas fa-save mr-1"></i> Save</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm ml-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
