<div class="row">
<div class="form-group col-md-6">
            <label for="sku" class="form-label">SKU <span class="text-muted font-weight-light small">(blank = auto)</span></label>
            <div class="input-group">
                <input type="text" name="sku" id="sku" class="form-control" placeholder="Leave blank to auto-generate" value="{{ old('sku', $form->sku ?? '') }}">
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary" id="sku-generate" title="Suggest the next SKU"><i class="fas fa-magic"></i> Generate</button>
                </div>
            </div>
            @error('sku')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="form-group col-md-6">
            <label for="name" class="form-label">Name *</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $form->name ?? '') }}">
            @error('name')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="form-group col-md-6">
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
<div class="form-group col-md-6">
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
<div class="form-group col-md-6">
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
<div class="form-group col-md-6">
            <label for="buy_price" class="form-label">Buy Price</label>
            <input type="number" step="0.01" name="buy_price" id="buy_price" class="form-control" value="{{ old('buy_price', $form->buy_price ?? '') }}">
            @error('buy_price')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label for="sell_price" class="form-label">Sell Price</label>
            <input type="number" step="0.01" name="sell_price" id="sell_price" class="form-control" value="{{ old('sell_price', $form->sell_price ?? '') }}">
            @error('sell_price')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label for="reorder_level" class="form-label">Reorder Level (low stock alert)</label>
            <input type="number" step="0.01" min="0" name="reorder_level" id="reorder_level" class="form-control" placeholder="e.g. 10" value="{{ old('reorder_level', $form->reorder_level ?? '') }}">
            @error('reorder_level')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="form-group col-md-6">
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
<div class="form-group col-md-6">
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
<div class="form-group col-md-6">
            <label for="unit" class="form-label">Unit</label>
            <select name="unit" id="unit" class="form-control @error('unit') is-invalid @enderror">
                <option value="">-- Select --</option>
                @foreach(($relations['units'] ?? []) as $u)
                    <option value="{{ $u }}" {{ old('unit', $form->unit ?? '') == $u ? 'selected' : '' }}>{{ $u }}</option>
                @endforeach
                <option value="__other__" {{ old('unit', $form->unit ?? '') && !in_array(old('unit', $form->unit ?? ''), ($relations['units'] ?? [])) ? 'selected' : '' }}>Other</option>
            </select>
            @error('unit')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="form-group col-md-6">
            <label for="pack_qty" class="form-label">Pack Qty</label>
            <input type="number" name="pack_qty" id="pack_qty" class="form-control" value="{{ old('pack_qty', $form->pack_qty ?? '') }}">
            @error('pack_qty')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="form-group col-md-6">
            <label for="pack_type" class="form-label">Pack Type</label>
            <select name="pack_type" id="pack_type" class="form-control @error('pack_type') is-invalid @enderror">
                <option value="">-- Select --</option>
                @foreach(($relations['pack_types'] ?? []) as $pt)
                    <option value="{{ $pt }}" {{ old('pack_type', $form->pack_type ?? '') == $pt ? 'selected' : '' }}>{{ $pt }}</option>
                @endforeach
            </select>
            @error('pack_type')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="form-group col-md-6">
            <label for="weight_kg" class="form-label">Weight (kg)</label>
            <input type="number" step="0.01" name="weight_kg" id="weight_kg" class="form-control" value="{{ old('weight_kg', $form->weight_kg ?? '') }}">
            @error('weight_kg')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="form-group col-md-6">
            <label class="form-label">Dimensions (L &times; W &times; H, cm)</label>
            <div class="row">
                <div class="col-4">
                    <input type="number" step="0.01" name="length_cm" id="length_cm" class="form-control" placeholder="Length" value="{{ old('length_cm', $form->length_cm ?? '') }}">
                    @error('length_cm')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-4">
                    <input type="number" step="0.01" name="width_cm" id="width_cm" class="form-control" placeholder="Width" value="{{ old('width_cm', $form->width_cm ?? '') }}">
                    @error('width_cm')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-4">
                    <input type="number" step="0.01" name="height_cm" id="height_cm" class="form-control" placeholder="Height" value="{{ old('height_cm', $form->height_cm ?? '') }}">
                    @error('height_cm')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
<div class="form-group col-md-6">
            <label for="specifications" class="form-label">Specifications</label>
            <textarea name="specifications" id="specifications" rows="3" class="form-control @error('specifications') is-invalid @enderror">{{ old('specifications', $form->specifications ?? '') }}</textarea>
            @error('specifications')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="form-group col-md-6">
            <label for="certificates" class="form-label">Certificates</label>
            <textarea name="certificates" id="certificates" rows="3" class="form-control @error('certificates') is-invalid @enderror">{{ old('certificates', $form->certificates ?? '') }}</textarea>
            @error('certificates')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
<div class="form-group col-md-6">
            <div class="form-check"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input @error('is_active') is-invalid @enderror" {{ old('is_active', $form->is_active ?? false) ? 'checked' : '' }}><label class="form-check-label" for="is_active">Active</label><div class="invalid-feedback">@error('is_active') {{ $message }} @enderror</div></div>
            @error('is_active')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>
</div>

@push('scripts')
<script>
    document.getElementById('sku-generate')?.addEventListener('click', async function () {
        var skuInput = document.getElementById('sku');
        try {
            var response = await fetch('{{ route('products.sku.next') }}', { headers: { 'Accept': 'application/json' } });
            if (!response.ok) { throw new Error('request failed'); }
            var data = await response.json();
            skuInput.value = data.sku;
        } catch (error) {
            skuInput.value = 'SKU-' + (Math.floor(Date.now() / 1000) % 100000);
        }
    });
</script>
@endpush
