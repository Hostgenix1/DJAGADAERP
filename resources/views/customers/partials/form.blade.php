<div class="row">
    <div class="col-md-6 col-field">
        <label for="company_name" class="form-label">Company Name *</label>
        <input type="text" name="company_name" id="company_name" class="form-control" value="{{ old('company_name', $form->company_name ?? '') }}">
        @error('company_name') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6 col-field">
        <label for="tax_registration_number" class="form-label">Tax Registration Number / VAT Number</label>
        <input type="text" name="tax_registration_number" id="tax_registration_number" class="form-control" maxlength="20" placeholder="Optional — e.g. 100123456700003" value="{{ old('tax_registration_number', $form->tax_registration_number ?? '') }}">
        @error('tax_registration_number') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6 col-field">
        <label for="contact_person" class="form-label">Contact Person</label>
        <input type="text" name="contact_person" id="contact_person" class="form-control" value="{{ old('contact_person', $form->contact_person ?? '') }}">
        @error('contact_person') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6 col-field">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $form->email ?? '') }}">
        @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6 col-field">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" name="phone" id="phone" class="form-control" placeholder="+971 XX XXX XXXX" value="{{ old('phone', $form->phone ?? '') }}">
        @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6 col-field">
        <label for="address" class="form-label">Address</label>
        <textarea name="address" id="address" class="form-control" rows="2">{{ old('address', $form->address ?? '') }}</textarea>
        @error('address') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-3 col-field">
        <label for="city" class="form-label">City</label>
        <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $form->city ?? '') }}">
        @error('city') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-3 col-field">
        <label for="emirate" class="form-label">Emirate</label>
        <select name="emirate" id="emirate" class="form-control">
            <option value="">-- Select --</option>
            @foreach(['Abu Dhabi', 'Dubai', 'Sharjah', 'Ajman', 'Umm Al Quwain', 'Ras Al Khaimah', 'Fujairah'] as $e)
                <option value="{{ $e }}" {{ old('emirate', $form->emirate ?? '') === $e ? 'selected' : '' }}>{{ $e }}</option>
            @endforeach
        </select>
        @error('emirate') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-3 col-field">
        <label for="po_box" class="form-label">PO Box</label>
        <input type="text" name="po_box" id="po_box" class="form-control" placeholder="e.g. 12345" value="{{ old('po_box', $form->po_box ?? '') }}">
        @error('po_box') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-3 col-field">
        <label for="postal_code" class="form-label">Postal Code</label>
        <input type="text" name="postal_code" id="postal_code" class="form-control" value="{{ old('postal_code', $form->postal_code ?? '') }}">
        @error('postal_code') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6 col-field">
        <label for="country" class="form-label">Country</label>
        @php
            $currentCountry = old('country', $form->country ?? '');
            $countries = config('countries', []);
            if ($currentCountry && !in_array($currentCountry, $countries)) { $countries[] = $currentCountry; sort($countries); }
        @endphp
        <select name="country" id="country" class="form-control">
            <option value="">-- Search / Select Country --</option>
            @foreach($countries as $c)
                <option value="{{ $c }}" {{ $currentCountry === $c ? 'selected' : '' }}>{{ $c }}</option>
            @endforeach
        </select>
        @error('country') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6 col-field">
        <label for="currency_id" class="form-label">Currency</label>
        <select name="currency_id" id="currency_id" class="form-control">
            <option value="">-- Select --</option>
            @foreach(($relations['currency_id'] ?? []) as $id => $code)
                <option value="{{ $id }}" {{ old('currency_id', $form->currency_id ?? '') == $id ? 'selected' : '' }}>{{ $code }}</option>
            @endforeach
        </select>
        @error('currency_id') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>
    <div class="col-md-6 col-field">
        <label for="is_active" class="form-label d-block">&nbsp;</label>
        <div class="custom-control custom-checkbox">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" id="is_active" class="custom-control-input" value="1" {{ old('is_active', $form->is_active ?? 1) ? 'checked' : '' }}>
            <label class="custom-control-label" for="is_active">Active</label>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@2.2.2/dist/select2-bootstrap4.min.css">
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(function () {
            $('#country').select2({ theme: 'bootstrap4', width: '100%', placeholder: '-- Search / Select Country --', allowClear: true });
        });
    </script>
@endpush
