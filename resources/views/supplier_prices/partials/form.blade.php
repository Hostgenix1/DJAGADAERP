<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Supplier</label>
            <select name="supplier_id" class="form-control">
                <option value="">-- Select --</option>
                @foreach($suppliers as $id => $n)
                    <option value="{{ $id }}" {{ old('supplier_id', ($form ?? null)?->supplier_id ?? '') == $id ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Product</label>
            <select name="product_id" class="form-control">
                <option value="">-- Select --</option>
                @foreach($products as $id => $n)
                    <option value="{{ $id }}" {{ old('product_id', ($form ?? null)?->product_id ?? '') == $id ? 'selected' : '' }}>{{ $n }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Packaging / Unit</label>
            <input type="text" name="packaging" class="form-control" placeholder="e.g. 25kg bag, carton of 12" value="{{ old('packaging', ($form ?? null)?->packaging ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Origin</label>
            <input type="text" name="origin" class="form-control" placeholder="e.g. China, India" value="{{ old('origin', ($form ?? null)?->origin ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Supplier Price *</label>
            <input type="number" step="0.01" min="0" name="supplier_price" class="form-control" value="{{ old('supplier_price', ($form ?? null)?->supplier_price ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Currency</label>
            <select name="currency_id" class="form-control">
                <option value="">-- Select --</option>
                @foreach($currencies as $id => $c)
                    <option value="{{ $id }}" {{ old('currency_id', ($form ?? null)?->currency_id ?? '') == $id ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Incoterm</label>
            <select name="incoterm" class="form-control">
                <option value="">-- Select --</option>
                @foreach($incoterms as $inc)
                    <option value="{{ $inc }}" {{ old('incoterm', ($form ?? null)?->incoterm ?? '') === $inc ? 'selected' : '' }}>{{ $inc }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Destination / Port</label>
            <input type="text" name="destination_port" class="form-control" placeholder="e.g. Jebel Ali, Dubai" value="{{ old('destination_port', ($form ?? null)?->destination_port ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Quantity</label>
            <input type="number" step="0.01" min="0" name="quantity" class="form-control" value="{{ old('quantity', ($form ?? null)?->quantity ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Container Quantity</label>
            <input type="number" step="0.01" min="0" name="container_quantity" class="form-control" value="{{ old('container_quantity', ($form ?? null)?->container_quantity ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Container Type</label>
            <select name="container_type" class="form-control">
                <option value="">-- N/A --</option>
                <option value="20ft" {{ old('container_type', ($form ?? null)?->container_type ?? '') === '20ft' ? 'selected' : '' }}>20 ft</option>
                <option value="40ft" {{ old('container_type', ($form ?? null)?->container_type ?? '') === '40ft' ? 'selected' : '' }}>40 ft</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Date Received *</label>
            <input type="date" name="date_received" class="form-control" value="{{ old('date_received', ($form ?? null)?->date_received?->format('Y-m-d') ?? date('Y-m-d')) }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Valid Until</label>
            <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until', ($form ?? null)?->valid_until?->format('Y-m-d') ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Source *</label>
            <select name="source" class="form-control" required>
                @foreach(['whatsapp', 'email', 'other'] as $src)
                    <option value="{{ $src }}" {{ old('source', ($form ?? null)?->source ?? 'other') === $src ? 'selected' : '' }}>{{ ucfirst($src) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" class="form-control" rows="2">{{ old('notes', ($form ?? null)?->notes ?? '') }}</textarea>
        </div>
    </div>
</div>
