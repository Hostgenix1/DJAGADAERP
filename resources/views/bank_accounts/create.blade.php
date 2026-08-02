@extends('layouts.app')

@section('title', 'New Bank Account')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-university mr-1"></i> New Bank Account</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('bank-accounts.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="bank_name">Bank Name <span class="text-danger">*</span></label>
                        <input type="text" name="bank_name" id="bank_name"
                               class="form-control @error('bank_name') is-invalid @enderror"
                               value="{{ old('bank_name') }}" required>
                        @error('bank_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="account_name">Account Name <span class="text-danger">*</span></label>
                        <input type="text" name="account_name" id="account_name"
                               class="form-control @error('account_name') is-invalid @enderror"
                               value="{{ old('account_name') }}" required>
                        @error('account_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="account_number">Account Number</label>
                                <input type="text" name="account_number" id="account_number"
                                       class="form-control @error('account_number') is-invalid @enderror"
                                       value="{{ old('account_number') }}">
                                @error('account_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="iban">IBAN</label>
                                <input type="text" name="iban" id="iban"
                                       class="form-control @error('iban') is-invalid @enderror"
                                       value="{{ old('iban') }}">
                                @error('iban')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="swift_code">SWIFT Code</label>
                                <input type="text" name="swift_code" id="swift_code"
                                       class="form-control @error('swift_code') is-invalid @enderror"
                                       value="{{ old('swift_code') }}">
                                @error('swift_code')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="currency_id">Currency <span class="text-danger">*</span></label>
                                <select name="currency_id" id="currency_id"
                                        class="form-control @error('currency_id') is-invalid @enderror" required>
                                    <option value="">-- Select --</option>
                                    @foreach($currencies as $id => $code)
                                        <option value="{{ $id }}" {{ old('currency_id') == $id ? 'selected' : '' }}>{{ $code }}</option>
                                    @endforeach
                                </select>
                                @error('currency_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="hidden" name="is_default" value="0">
                                    <input type="checkbox" name="is_default" id="is_default"
                                           class="custom-control-input" value="1"
                                           {{ old('is_default') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_default">Default for this currency</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" id="is_active"
                                           class="custom-control-input" value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                        <a href="{{ route('bank-accounts.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
