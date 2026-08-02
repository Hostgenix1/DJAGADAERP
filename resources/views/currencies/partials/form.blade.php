<div class="row">
<div class="col-md-6 col-field">
            <label for="code" class="form-label">Code *</label>
            <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $form->code ?? '') }}">
            @error('code')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="name" class="form-label">Name *</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $form->name ?? '') }}">
            @error('name')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="symbol" class="form-label">Symbol</label>
            <input type="text" name="symbol" id="symbol" class="form-control" value="{{ old('symbol', $form->symbol ?? '') }}">
            @error('symbol')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="rate" class="form-label">Rate (vs base)</label>
            <input type="number" step="0.01" name="rate" id="rate" class="form-control" value="{{ old('rate', $form->rate ?? '') }}">
            @error('rate')
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
