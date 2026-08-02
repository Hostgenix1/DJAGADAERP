<div class="row">
<div class="col-md-6 col-field">
            <label for="sku" class="form-label">SKU *</label>
            <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku', $form->sku ?? '') }}">
            @error('sku')
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
            <label for="brand_id" class="form-label">Brand</label>
                                    <select name="brand_id" id="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
                            <option value="">-- Select --</option>
                            @foreach(($relations['brand_id'] ?? []) as $id => $label)
                                <option value="{{ $id }}" {{ old('brand_id', $form->brand_id ?? null) == $id ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
            @error('brand_id')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="category_id" class="form-label">Category</label>
                                    <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                            <option value="">-- Select --</option>
                            @foreach(($relations['category_id'] ?? []) as $id => $label)
                                <option value="{{ $id }}" {{ old('category_id', $form->category_id ?? null) == $id ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
            @error('category_id')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="supplier_id" class="form-label">Supplier</label>
                                    <select name="supplier_id" id="supplier_id" class="form-control @error('supplier_id') is-invalid @enderror">
                            <option value="">-- Select --</option>
                            @foreach(($relations['supplier_id'] ?? []) as $id => $label)
                                <option value="{{ $id }}" {{ old('supplier_id', $form->supplier_id ?? null) == $id ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
            @error('supplier_id')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="buy_price" class="form-label">Buy Price</label>
            <input type="number" step="0.01" name="buy_price" id="buy_price" class="form-control" value="{{ old('buy_price', $form->buy_price ?? '') }}">
            @error('buy_price')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="sell_price" class="form-label">Sell Price</label>
            <input type="number" step="0.01" name="sell_price" id="sell_price" class="form-control" value="{{ old('sell_price', $form->sell_price ?? '') }}">
            @error('sell_price')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="currency_id" class="form-label">Currency</label>
                                    <select name="currency_id" id="currency_id" class="form-control @error('currency_id') is-invalid @enderror">
                            <option value="">-- Select --</option>
                            @foreach(($relations['currency_id'] ?? []) as $id => $label)
                                <option value="{{ $id }}" {{ old('currency_id', $form->currency_id ?? null) == $id ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
            @error('currency_id')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="tax_id" class="form-label">Tax</label>
                                    <select name="tax_id" id="tax_id" class="form-control @error('tax_id') is-invalid @enderror">
                            <option value="">-- Select --</option>
                            @foreach(($relations['tax_id'] ?? []) as $id => $label)
                                <option value="{{ $id }}" {{ old('tax_id', $form->tax_id ?? null) == $id ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
            @error('tax_id')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="unit" class="form-label">Unit</label>
            <input type="text" name="unit" id="unit" class="form-control" value="{{ old('unit', $form->unit ?? '') }}">
            @error('unit')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="pack_qty" class="form-label">Pack Qty</label>
            <input type="number" name="pack_qty" id="pack_qty" class="form-control" value="{{ old('pack_qty', $form->pack_qty ?? '') }}">
            @error('pack_qty')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="pack_type" class="form-label">Pack Type</label>
            <select name="pack_type" id="pack_type" class="form-control @error('pack_type') is-invalid @enderror"><option value="">-- Select --</option><option value="carton" {{ old('pack_type', $form->pack_type ?? '') == 'carton' ? 'selected' : '' }}>carton</option><option value="box" {{ old('pack_type', $form->pack_type ?? '') == 'box' ? 'selected' : '' }}>box</option><option value="unit" {{ old('pack_type', $form->pack_type ?? '') == 'unit' ? 'selected' : '' }}>unit</option><option value="pallet" {{ old('pack_type', $form->pack_type ?? '') == 'pallet' ? 'selected' : '' }}>pallet</option></select>
            @error('pack_type')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="weight_kg" class="form-label">Weight (kg)</label>
            <input type="number" step="0.01" name="weight_kg" id="weight_kg" class="form-control" value="{{ old('weight_kg', $form->weight_kg ?? '') }}">
            @error('weight_kg')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-6 col-field">
            <label for="dimensions" class="form-label">Dimensions</label>
            <input type="text" name="dimensions" id="dimensions" class="form-control" value="{{ old('dimensions', $form->dimensions ?? '') }}">
            @error('dimensions')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-12 col-field">
            <label for="specifications" class="form-label">Specifications</label>
            <textarea name="specifications" id="specifications" rows="3" class="form-control @error('specifications') is-invalid @enderror">{{ old('specifications', $form->specifications ?? '') }}</textarea>
            @error('specifications')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="col-md-12 col-field">
            <label for="certificates" class="form-label">Certificates</label>
            <textarea name="certificates" id="certificates" rows="3" class="form-control @error('certificates') is-invalid @enderror">{{ old('certificates', $form->certificates ?? '') }}</textarea>
            @error('certificates')
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
