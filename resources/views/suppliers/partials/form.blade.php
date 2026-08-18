<div class="row">
<div class="col-md-6 col-field">
            <label for="company_name" class="form-label">Company Name *</label>
            <input type="text" name="company_name" id="company_name" class="form-control" value="{{ old('company_name', $form->company_name ?? '') }}">
            @error('company_name')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="contact_person" class="form-label">Contact Person</label>
            <input type="text" name="contact_person" id="contact_person" class="form-control" value="{{ old('contact_person', $form->contact_person ?? '') }}">
            @error('contact_person')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $form->email ?? '') }}">
            @error('email')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $form->phone ?? '') }}">
            @error('phone')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-12 col-field">
            <label for="address" class="form-label">Address</label>
            <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $form->address ?? '') }}</textarea>
            @error('address')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="city" class="form-label">City</label>
            <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $form->city ?? '') }}">
            @error('city')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="country" class="form-label">Country</label>
            <input type="text" name="country" id="country" class="form-control" value="{{ old('country', $form->country ?? '') }}">
            @error('country')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="tax_registration_number" class="form-label">Tax Registration Number / VAT Number</label>
            <input type="text" name="tax_registration_number" id="tax_registration_number" class="form-control" maxlength="20" value="{{ old('tax_registration_number', $form->tax_registration_number ?? '') }}">
            <small class="text-muted">Optional — shown on purchase documents.</small>
            @error('tax_registration_number')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="payment_terms" class="form-label">Payment Terms</label>
            <input type="text" name="payment_terms" id="payment_terms" class="form-control" value="{{ old('payment_terms', $form->payment_terms ?? '') }}">
            @error('payment_terms')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="currency_id" class="form-label">Default Currency</label>
            <select name="currency_id" id="currency_id" class="form-control">
                <option value="">-- Select --</option>
                @foreach($currencies ?? [] as $id=>$code)
                    <option value="{{ $id }}" {{ old('currency_id', $form->currency_id ?? '') == $id ? 'selected' : '' }}>{{ $code }}</option>
                @endforeach
            </select>
            @error('currency_id')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="default_payment_term" class="form-label">Default Payment Term (PO / Bills)</label>
            <select name="default_payment_term" id="default_payment_term" class="form-control">
                <option value="">-- Select --</option>
                @foreach($paymentTerms ?? [] as $pt)
                    <option value="{{ $pt }}" {{ old('default_payment_term', $form->default_payment_term ?? '') === $pt ? 'selected' : '' }}>{{ $pt }}</option>
                @endforeach
            </select>
            <small class="text-muted">Used when converting Purchase Orders to Supplier Bills.</small>
            @error('default_payment_term')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <div class="form-check"><input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input @error('is_active') is-invalid @enderror" {{ old('is_active', $form->is_active ?? false) ? 'checked' : '' }}><label class="form-check-label" for="is_active">Active</label><div class="invalid-feedback">@error('is_active') {{ $message }} @enderror</div></div>
            @error('is_active')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
</div>
