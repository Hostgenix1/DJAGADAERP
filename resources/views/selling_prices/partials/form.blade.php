<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>From Supplier Offer (optional)</label>
            <select name="supplier_price_id" id="sp-select" class="form-control">
                <option value="">-- Select Supplier Offer --</option>
                @foreach($supplierPrices as $sp)
                    <option value="{{ $sp->id }}"
                        data-cost="{{ $sp->supplier_price }}"
                        data-product="{{ $sp->product_id }}"
                        data-currency="{{ $sp->currency_id }}"
                        data-incoterm="{{ $sp->incoterm }}"
                        data-destination="{{ $sp->destination_port }}"
                        @selected(old('supplier_price_id', ($form ?? null)?->supplier_price_id ?? '') == $sp->id)>
                        {{ $sp->date_received?->format('d M Y') }} · {{ $sp->supplier?->company_name ?? '-' }} · {{ $sp->product?->name ?? '-' }} · {{ $sp->currency?->code ?? '' }} {{ number_format((float) $sp->supplier_price, 2) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Customer (blank = General price)</label>
            <select name="customer_id" class="form-control">
                <option value="">-- General Price --</option>
                @foreach($customers as $id => $n)
                    <option value="{{ $id }}" {{ old('customer_id', ($form ?? null)?->customer_id ?? '') == $id ? 'selected' : '' }}>{{ $n }}</option>
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
            <input type="text" name="packaging" class="form-control" value="{{ old('packaging', ($form ?? null)?->packaging ?? '') }}">
        </div>
    </div>
    @can('view-pricing-costs')
    <div class="col-md-4">
        <div class="form-group">
            <label>Supplier Cost *</label>
            <input type="number" step="0.01" min="0" name="supplier_cost" id="sp-cost" class="form-control" value="{{ old('supplier_cost', ($form ?? null)?->supplier_cost ?? 0) }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Margin % (or use amount)</label>
            <input type="number" step="0.001" min="0" name="margin_pct" id="sp-margin-pct" class="form-control" placeholder="e.g. 12.5" value="{{ old('margin_pct', ($form ?? null)?->margin_pct ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Margin Amount</label>
            <input type="number" step="0.01" min="0" name="margin_amount" id="sp-margin-amt" class="form-control" placeholder="Fixed amount" value="{{ old('margin_amount', ($form ?? null)?->margin_amount ?? 0) }}">
        </div>
    </div>
    @endcan
    <div class="col-md-4">
        <div class="form-group">
            <label>Selling Price *</label>
            <input type="number" step="0.01" min="0" name="selling_price" id="sp-selling" class="form-control" value="{{ old('selling_price', ($form ?? null)?->selling_price ?? '') }}" required>
            <small class="text-muted">Auto-computed from cost + margin; overwrite for a manual final price.</small>
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
            <label>Destination</label>
            <input type="text" name="destination" class="form-control" placeholder="e.g. Dakar, Senegal" value="{{ old('destination', ($form ?? null)?->destination ?? '') }}">
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
            <label>Minimum Quantity (MOQ)</label>
            <input type="number" step="0.01" min="0" name="min_qty" class="form-control" value="{{ old('min_qty', ($form ?? null)?->min_qty ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Valid Until</label>
            <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until', ($form ?? null)?->valid_until?->format('Y-m-d') ?? '') }}">
            <small class="text-muted">After this date the price auto-expires.</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Status *</label>
            <select name="status" class="form-control" required>
                @foreach(['draft', 'approved', 'expired'] as $st)
                    <option value="{{ $st }}" {{ old('status', ($form ?? null)?->status ?? 'draft') === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="d-block">&nbsp;</label>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="approved_for_ai" id="approved_for_ai" class="custom-control-input" value="1" {{ old('approved_for_ai', ($form ?? null)?->approved_for_ai ?? false) ? 'checked' : '' }}>
                <label class="custom-control-label" for="approved_for_ai">Approved for AI <i class="fas fa-robot text-info ml-1"></i></label>
                <small class="text-muted d-block">AI may quote this price to customers.</small>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" class="form-control" rows="2">{{ old('notes', ($form ?? null)?->notes ?? '') }}</textarea>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function () {
    // Supplier offer -> prefill cost/product/currency/incoterm/destination
    $('#sp-select').on('change', function () {
        const o = $(this).find(':selected');
        if (!o.val()) return;
        @can('view-pricing-costs')
        $('#sp-cost').val(o.data('cost'));
        @endcan
        if (o.data('product')) { $('select[name="product_id"]').val(o.data('product')); }
        if (o.data('currency')) { $('select[name="currency_id"]').val(o.data('currency')); }
        if (o.data('incoterm')) { $('select[name="incoterm"]').val(o.data('incoterm')); }
        if (o.data('destination')) { $('input[name="destination"]').val(o.data('destination')); }
        recalc();
    });

    function cost() { return parseFloat($('#sp-cost').val()) || 0; }

    function recalc() {
        @can('view-pricing-costs')
        const pct = parseFloat($('#sp-margin-pct').val());
        const amtEl = $('#sp-margin-amt');
        if (!isNaN(pct) && pct > 0) {
            amtEl.val((cost() * pct / 100).toFixed(2));
            $('#sp-selling').val((cost() + parseFloat(amtEl.val())).toFixed(2));
            return;
        }
        const amt = parseFloat(amtEl.val());
        if (!isNaN(amt) && amt > 0) {
            $('#sp-selling').val((cost() + amt).toFixed(2));
        }
        @endcan
    }

    @can('view-pricing-costs')
    $('#sp-margin-pct, #sp-margin-amt').on('input', recalc);
    $('#sp-cost').on('input', recalc);
    @endcan
});
</script>
@endpush
