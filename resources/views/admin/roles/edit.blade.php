@extends('layouts.app')

@section('title', 'Edit Role')

{{--
  Edit Role - Settings Module
  Module: Settings
  Features: Role edit form, grouped permission checkboxes with pre-selection, check all/uncheck all buttons, group select-all sync, PUT method override, validation errors
  Version: 1.0.0
--}}

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title">Edit Role: {{ $role->name }}</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Role Name <span class="text-danger">*</span></label>
                        <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role->name) }}" required>
                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>

                    <hr>

                    <div class="mb-2">
                        <button type="button" id="check-all" class="btn btn-sm btn-outline-success"><i class="fas fa-check-double"></i> Check All</button>
                        <button type="button" id="uncheck-all" class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i> Uncheck All</button>
                    </div>

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
                                                           {{ in_array($name, $has) ? 'checked' : '' }}>
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
        function syncGroupCards($card) {
            var all = $card.find('.perm-check').length === $card.find('.perm-check:checked').length;
            $card.find('.group-check').prop('checked', all);
        }

        $('#permission-groups').on('change', '.group-check', function () {
            var $card = $(this).closest('.card');
            $card.find('.perm-check').prop('checked', $(this).prop('checked'));
        });

        $('#permission-groups').on('change', '.perm-check', function () {
            syncGroupCards($(this).closest('.card'));
        });

        $('#check-all').on('click', function () {
            $('.perm-check').prop('checked', true);
            $('.group-check').prop('checked', true);
        });

        $('#uncheck-all').on('click', function () {
            $('.perm-check').prop('checked', false);
            $('.group-check').prop('checked', false);
        });

        $('.card').each(function () { syncGroupCards($(this)); });
    });
</script>
@endpush