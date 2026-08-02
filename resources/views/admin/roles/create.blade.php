@extends('layouts.app')

@section('title', 'New Role')

{{--
  Create Role - Settings Module
  Module: Settings
  Features: Role creation form, grouped permission checkboxes, group select-all toggle, permission name input, validation, save/cancel actions
  Version: 1.0.0
--}}

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-shield mr-1"></i> New Role</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.roles.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="name">Role Name <span class="text-danger">*</span></label>
                        <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <hr>

                    <div id="permission-groups">
                        @foreach($permissions as $group => $perms)
                            <div class="card mb-2">
                                <div class="card-header py-2">
                                    <div class="form-check">
                                        <input class="form-check-input group-check" type="checkbox" data-group="{{ $group }}">
                                        <label class="form-check-label mb-0">{{ ucfirst($group) }}</label>
                                    </div>
                                </div>
                                <div class="card-body py-2">
                                    <div class="row">
                                        @foreach($perms as $name => $action)
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input perm-check" type="checkbox" name="permissions[]"
                                                           value="{{ $name }}" id="perm-{{ Str::slug($group) }}-{{ $name }}"
                                                           data-group="{{ $group }}"
                                                           {{ in_array($name, old('permissions', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="perm-{{ Str::slug($group) }}-{{ $name }}">{{ $action }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('#permissions-groups','#permission-groups').on('change', '.group-check', function () {
            var $card = $(this).closest('.card');
            $card.find('.perm-check').prop('checked', $(this).prop('checked'));
        });
        $('.perm-check').on('change', function () {
            var $card = $(this).closest('.card');
            var all = $card.find('.perm-check').length === $card.find('.perm-check:checked').length;
            $card.find('.group-check').prop('checked', all);
        });
    });
</script>
@endpush
