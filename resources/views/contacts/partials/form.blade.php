<div class="row">
<div class="col-md-6 col-field">
            <label for="customer_id" class="form-label">Customer</label>
                                    <select name="customer_id" id="customer_id" class="form-control @error('customer_id') is-invalid @enderror">
                            <option value="">-- Select --</option>
                            @foreach(($relations['customer_id'] ?? []) as $id => $label)
                                <option value="{{ $id }}" {{ old('customer_id', $form->customer_id ?? null) == $id ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
            @error('customer_id')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="full_name" class="form-label">Full Name *</label>
            <input type="text" name="full_name" id="full_name" class="form-control" value="{{ old('full_name', $form->full_name ?? '') }}">
            @error('full_name')
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
<div class="col-md-6 col-field">
            <label for="position" class="form-label">Position</label>
            <input type="text" name="position" id="position" class="form-control" value="{{ old('position', $form->position ?? '') }}">
            @error('position')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <div class="form-check"><input type="checkbox" name="is_primary" id="is_primary" value="1" class="form-check-input @error('is_primary') is-invalid @enderror" {{ old('is_primary', $form->is_primary ?? false) ? 'checked' : '' }}><label class="form-check-label" for="is_primary">Primary Contact</label><div class="invalid-feedback">@error('is_primary') {{ $message }} @enderror</div></div>
            @error('is_primary')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
</div>
