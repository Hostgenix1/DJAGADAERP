@extends('layouts.app')
@section('title', 'New Order')

@section('content')
<form method="POST" action="{{ route('orders.store') }}">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-shopping-cart mr-1"></i> New Order</h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Customer *</label>
                            <select name="customer_id" class="form-control" required>
                                <option value="">-- Select --</option>
                                @foreach($customers as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Order Date *</label>
                            <input type="date" name="order_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Expected Delivery</label>
                            <input type="date" name="expected_delivery" class="form-control" value="{{ now()->addDays(14)->format('Y-m-d') }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Currency</label>
                            <select name="currency_id" class="form-control">
                                <option value="">-- Default --</option>
                                @foreach($currencies as $id => $code)
                                    <option value="{{ $id }}">{{ $code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Payment Terms</label>
                        <select name="payment_terms" class="form-control" id="payment-terms-select">
                            <option value="">-- Select --</option>
                            @foreach($paymentTerms as $pt)
                                <option value="{{ $pt }}">{{ $pt }}</option>
                            @endforeach
                            <option value="Custom">-- Custom --</option>
                        </select>
                        <textarea name="payment_terms_custom" id="payment-terms-custom" class="form-control mt-1 d-none" rows="2" placeholder="Custom payment terms..."></textarea>
                    </div>

                    <h5>Line Items</h5>
                    <table class="table table-sm" id="items-table">
                        <thead>
                        <tr>
                            <th style="width:30%">Description</th>
                            <th>Qty</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Tax %</th>
                            <th>Disc %</th>
                            <th>Line Total</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                        <tr>
                            <td colspan="6" class="text-right"><strong>Subtotal</strong></td>
                            <td colspan="2"><strong id="subtotal">0.00</strong></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-right"><strong>Tax</strong></td>
                            <td colspan="2"><strong id="tax-total">0.00</strong></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-right"><strong>Discount</strong></td>
                            <td colspan="2"><input type="number" step="0.01" name="discount" id="discount-input" class="form-control form-control-sm" style="width:100px" value="0"></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-right"><strong>Grand Total</strong></td>
                            <td colspan="2"><strong id="grand-total">0.00</strong></td>
                        </tr>
                        </tfoot>
                    </table>
                    <button type="button" class="btn btn-sm btn-success mb-3" id="add-item"><i class="fas fa-plus"></i> Add Item</button>

                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-block btn-sm"><i class="fas fa-save mr-1"></i> Save Order</button>
                    <a href="{{ route('orders.index') }}" class="btn btn-default btn-block btn-sm">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>

@php
$productsJson = $products->map(fn($p) => ['id'=>$p->id,'name'=>$p->name,'price'=>(float)$p->sell_price,'unit'=>$p->unit ?? ''])->values();
$ratesJson = $rates->map(fn($r,$id)=>[(int)$id, (float)$r])->values()->toArray();
@endphp
@endsection

@push('scripts')
<script>
$(function () {
    const products = @json($productsJson);
    const rates = Object.fromEntries(@json($ratesJson));
    const units = @json($units ?? []);
    let idx = 0;

    function rateFor(){
        const id = $('select[name="currency_id"]').val();
        return id && rates[id] ? parseFloat(rates[id]) : 1;
    }
    function conv(base){ return Math.round(base * rateFor() * 100) / 100; }

    function unitInput(name,val){
        const v = val||'';
        const isCustom = v && !units.includes(v);
        const customDisabled = isCustom ? '' : 'disabled';
        const selectDisabled = isCustom ? 'disabled' : '';
        return `<select name="${name}" class="form-control form-control-sm unit-select" ${selectDisabled}><option value="">-- Select Unit --</option>${units.map(u=>`<option value="${u}" ${u===v?'selected':''}>${u}</option>`).join('')}<option value="__other__">-- Other --</option></select><input type="text" name="${name}" class="form-control form-control-sm mt-1 unit-custom ${isCustom?'':'d-none'}" ${customDisabled} value="${v}" placeholder="Custom unit">`;
    }

    function addRow(data) {
        const base = data?.price ? parseFloat(data.price) : 0;
        const row = `<tr data-idx="${idx}">
            <td><select name="items[${idx}][product_id]" class="form-control form-control-sm prod-select"><option value="">Manual</option>${products.map(p=>`<option value="${p.id}" data-price="${p.price}" data-unit="${p.unit}">${p.name}</option>`).join('')}</select><input type="text" name="items[${idx}][description]" class="form-control form-control-sm mt-1" placeholder="Description" value="${data?.description||''}" required></td>
            <td><input type="number" step="0.01" name="items[${idx}][qty]" class="form-control form-control-sm qty" value="${data?.qty||1}" min="0.01" required></td>
            <td>${unitInput(`items[${idx}][unit]`, data?.unit||'')}</td>
            <td><input type="number" step="0.01" name="items[${idx}][unit_price]" class="form-control form-control-sm price" data-base-price="${base}" value="${base?conv(base):(data?.price||0)}" min="0" required></td>
            <td><input type="number" step="0.01" name="items[${idx}][tax_rate]" class="form-control form-control-sm tax" value="${data?.tax||0}" min="0"></td>
            <td><input type="number" step="0.01" name="items[${idx}][discount_pct]" class="form-control form-control-sm disc" value="${data?.disc||0}" min="0"></td>
            <td class="line-total">0.00</td>
            <td><button type="button" class="btn btn-xs btn-danger remove-row"><i class="fas fa-times"></i></button></td>
        </tr>`;
        $('#items-table tbody').append(row);
        idx++;
        recalc();
    }

    function recalc() {
        let sub = 0, tax = 0;
        $('#items-table tbody tr').each(function() {
            const $r = $(this);
            const qty = parseFloat($r.find('.qty').val()) || 0;
            const price = parseFloat($r.find('.price').val()) || 0;
            const taxR = parseFloat($r.find('.tax').val()) || 0;
            const disc = parseFloat($r.find('.disc').val()) || 0;
            const base = qty * price;
            const afterDisc = base - (base * disc / 100);
            const lineTax = afterDisc * taxR / 100;
            $r.find('.line-total').text((afterDisc + lineTax).toFixed(2));
            sub += afterDisc;
            tax += lineTax;
        });
        $('#subtotal').text(sub.toFixed(2));
        $('#tax-total').text(tax.toFixed(2));
        const discount = parseFloat($('#discount-input').val()) || 0;
        $('#grand-total').text((sub + tax - discount).toFixed(2));
    }

    $('#add-item').click(() => addRow());
    $(document).on('click', '.remove-row', function() { $(this).closest('tr').remove(); recalc(); });
    $(document).on('change keyup', '.qty,.price,.tax,.disc,#discount-input', recalc);
    $('#payment-terms-select').on('change', function(){
        $('#payment-terms-custom').toggleClass('d-none', $(this).val() !== 'Custom');
    });
    $(document).on('change', '.prod-select', function() {
        const opt = $(this).find(':selected');
        const $row = $(this).closest('tr');
        const base = parseFloat(opt.data('price')) || 0;
        $row.find('.price').val(base ? conv(base) : 0).attr('data-base-price', base);
        const u = opt.data('unit') || '';
        const $sel = $row.find('.unit-select'), $cust = $row.find('.unit-custom');
        if (u && !units.includes(u)) { $cust.removeClass('d-none').val(u).prop('disabled', false); $sel.prop('disabled', true).val(''); }
        else { $sel.val(u).prop('disabled', false); $cust.addClass('d-none').val('').prop('disabled', true); }
        $row.find('input[name$="[description]"]').val(opt.text() !== 'Manual' ? opt.text() : '');
        recalc();
    });
    $(document).on('change', '.unit-select', function() {
        const $u = $(this), $r = $u.closest('tr'), custom = $r.find('.unit-custom');
        if ($u.val() === '__other__') { $u.prop('disabled', true); custom.removeClass('d-none').prop('disabled', false).val('').focus(); }
        else { custom.addClass('d-none').prop('disabled', true).val(''); }
        recalc();
    });

    $('select[name="currency_id"]').on('change', function() {
        const r = rateFor();
        $('#items-table tbody tr').each(function() {
            const $price = $(this).find('.price');
            const base = parseFloat($price.data('base-price')) || 0;
            $price.val(base ? conv(base) : '');
        });
        recalc();
    });

    addRow();
});
</script>
@endpush
