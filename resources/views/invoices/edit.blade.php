@extends('layouts.app')
@section('title', 'Edit '.$invoice->number)

{{--
  Edit Invoice - Invoices Module
  Module: Invoices
  Features: Dynamic line items with existing data, product auto-fill, auto-calculate totals, invoice type/customer/date/currency, PUT method override, VAT + shipment fields
  Version: 2.0.0
--}}

@section('content')
<form method="POST" action="{{ route('invoices.update', $invoice) }}">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-file-invoice-dollar mr-1"></i> Edit {{ $invoice->number }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>Type *</label>
                            <select name="type" class="form-control" id="invoice-type-select" required>
                                @foreach($types as $t)<option value="{{ $t }}" {{ $invoice->type===$t?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$t)) }}</option>@endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Customer *</label>
                            <select name="customer_id" class="form-control" required>
                                @foreach($customers as $id=>$n)<option value="{{ $id }}" {{ $invoice->customer_id==$id?'selected':'' }}>{{ $n }}</option>@endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Date *</label>
                            <input type="date" name="invoice_date" class="form-control" value="{{ $invoice->invoice_date?->format('Y-m-d') }}" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label>Due Date</label>
                            <input type="date" name="due_date" class="form-control" value="{{ $invoice->due_date?->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>Reference No</label>
                            <input type="text" name="reference_no" class="form-control" value="{{ $invoice->reference_no }}" placeholder="PO-1042">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Offer Valid (days)</label>
                            <input type="number" name="offer_valid" class="form-control" value="{{ $invoice->offer_valid ?? 10 }}" min="1" max="365">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Currency</label>
                            <select name="currency_id" class="form-control"><option value="">-- Default --</option>@foreach($currencies as $id=>$c)<option value="{{ $id }}" {{ $invoice->currency_id==$id?'selected':'' }}>{{ $c }}</option>@endforeach</select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Bank Account</label>
                            <select name="bank_account_id" class="form-control" id="bank-account-select">
                                <option value="">-- Select Bank Account --</option>
                                @foreach($bankAccounts as $ba)
                                    <option value="{{ $ba->id }}" data-currency="{{ $ba->currency_id }}" {{ $invoice->bank_account_id == $ba->id ? 'selected' : '' }}>{{ $ba->bank_name }} - {{ $ba->account_name }} ({{ $ba->currency?->code }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Auto-fills bank details on invoice PDF</small>
                        </div>
                    </div>

                    <div class="card card-outline card-info mb-3">
                        <div class="card-header py-2"><h5 class="card-title mb-0"><i class="fas fa-percent mr-1"></i> VAT</h5></div>
                        <div class="card-body py-2">
                            <div class="form-row">
                                <div class="form-group col-md-4 mb-0">
                                    <label>VAT Mode</label>
                                    <select name="vat_mode" class="form-control" id="vat-mode">
                                        @foreach(config('invoice.vat_modes') as $k=>$v)
                                            <option value="{{ $k }}" {{ $invoice->vat_mode===$k?'selected':'' }}>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4 mb-0">
                                    <label>VAT Rate</label>
                                    <select name="vat_rate" class="form-control" id="vat-rate-select">
                                        @php $customRate = $invoice->vat_rate !== null && !$taxes->contains(fn($t)=>$t->rate == $invoice->vat_rate); @endphp
                                        @foreach($taxes as $tax)
                                            <option value="{{ $tax->rate }}" {{ (float) $invoice->vat_rate === (float) $tax->rate ? 'selected':'' }}>{{ $tax->name }} ({{ rtrim(rtrim($tax->rate,'0'),'.') }}%)</option>
                                        @endforeach
                                        <option value="custom" {{ $customRate ? 'selected':'' }}>-- Custom --</option>
                                    </select>
                                    <input type="number" step="0.001" name="vat_rate_custom" id="vat-rate-custom" class="form-control form-control-sm mt-1 {{ $customRate ? '':'d-none' }}" value="{{ $customRate ? $invoice->vat_rate : '' }}" placeholder="Custom rate %">
                                </div>
                                <div class="form-group col-md-4 mb-0">
                                    <label>Payment Terms</label>
                                    @php $isCustomTerms = $invoice->payment_terms && !in_array($invoice->payment_terms, $paymentTerms); @endphp
                                    <select name="payment_terms" class="form-control" id="payment-terms-select">
                                        <option value="">-- Select --</option>
                                        @foreach($paymentTerms as $pt)
                                            <option value="{{ $pt }}" {{ $invoice->payment_terms===$pt?'selected':'' }}>{{ $pt }}</option>
                                        @endforeach
                                        <option value="Custom" {{ $isCustomTerms?'selected':'' }}>-- Custom --</option>
                                    </select>
                                    <textarea name="payment_terms_custom" id="payment-terms-custom" class="form-control form-control-sm mt-1 {{ $isCustomTerms ? '':'d-none' }}" rows="2" placeholder="Custom payment terms...">{{ $isCustomTerms ? $invoice->payment_terms : '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Incoterm / Delivery Terms</label>
                            @php
                                $dt = old('delivery_terms', $invoice->delivery_terms);
                                $dtIsCustom = $dt && !in_array($dt, $incoterms);
                            @endphp
                            <select name="delivery_terms" class="form-control" id="delivery-terms-select">
                                <option value="">-- Select --</option>
                                @foreach($incoterms as $inc)
                                    <option value="{{ $inc }}" {{ !$dtIsCustom && $dt === $inc ? 'selected' : '' }}>{{ $inc }}</option>
                                @endforeach
                                <option value="Custom" {{ $dtIsCustom ? 'selected' : '' }}>-- Custom --</option>
                            </select>
                            <input type="text" name="delivery_terms_custom" id="delivery-terms-custom" class="form-control mt-1 {{ $dtIsCustom ? '' : 'd-none' }}" placeholder="Custom incoterm (e.g. CIF Dakar, Senegal)" value="{{ $dtIsCustom ? $dt : old('delivery_terms_custom') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Goods Origin</label>
                            <input type="text" name="goods_origin" class="form-control" value="{{ $invoice->goods_origin }}" placeholder="UAE / New Zealand...">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Port of Loading</label>
                            <input type="text" name="port_of_loading" class="form-control" value="{{ $invoice->port_of_loading }}" placeholder="Jebel Ali Port, Dubai">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Port of Discharge</label>
                            <input type="text" name="port_of_discharge" class="form-control" value="{{ $invoice->port_of_discharge }}" placeholder="Dakar transit to Guinea Conakry">
                        </div>
                    </div>

                    <h5>Line Items</h5>
                    <table class="table table-sm" id="items-table">
                        <thead><tr><th style="width:28%">Description</th><th>Qty</th><th>Unit</th><th>Price</th><th>Tax %</th><th>Disc %</th><th>Total</th><th></th></tr></thead>
                        <tbody></tbody>
                        <tfoot>
                        <tr><td colspan="6" class="text-right"><strong>Subtotal</strong></td><td colspan="2"><strong id="subtotal">0.00</strong></td></tr>
                        <tr><td colspan="6" class="text-right"><strong>Tax</strong></td><td colspan="2"><strong id="tax-total">0.00</strong></td></tr>
                        <tr><td colspan="6" class="text-right"><strong>Discount</strong></td><td colspan="2"><input type="number" step="0.01" name="discount" id="discount-input" class="form-control form-control-sm" style="width:100px" value="{{ $invoice->discount }}"></td></tr>
                        <tr><td colspan="6" class="text-right"><strong>Grand Total</strong></td><td colspan="2"><strong id="grand-total">0.00</strong></td></tr>
                        </tfoot>
                    </table>
                    <button type="button" class="btn btn-sm btn-success mb-3" id="add-item"><i class="fas fa-plus"></i> Add Item</button>
                    <div class="form-group"><label>Notes</label><textarea name="notes" class="form-control" rows="2">{{ $invoice->notes }}</textarea></div>
                    <div class="form-group"><label>Terms</label><textarea name="terms" class="form-control" rows="2">{{ $invoice->terms }}</textarea></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-block btn-sm"><i class="fas fa-save mr-1"></i> Update</button>
                    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-default btn-block btn-sm">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
@php
$productsJson = $products->map(fn($p)=>['id'=>$p->id,'name'=>$p->name,'price'=>(float)$p->sell_price,'unit'=>$p->unit ?? '','tax'=>$p->tax?->rate !== null ? (float)$p->tax->rate : null])->values();
$itemsJson = $invoice->items->map(fn($i)=>['product_id'=>$i->product_id,'description'=>$i->description,'sub_description'=>$i->sub_description,'qty'=>$i->qty,'unit'=>$i->unit,'price'=>$i->unit_price,'tax'=>$i->tax_rate,'disc'=>$i->discount_pct])->values();
$ratesJson = $rates->map(fn($r,$id)=>[(int)$id, (float)$r])->values()->toArray();
@endphp
@endsection

@push('scripts')
<script>
$(function(){
    const products = @json($productsJson);
    const existing = @json($itemsJson);
    const units = @json($units);
    const rates = Object.fromEntries(@json($ratesJson));
    let idx=0;

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
    function addRow(d){
        const priceInDoc = d?.price ? parseFloat(d.price) : 0;
        const base = priceInDoc ? Math.round(priceInDoc / rateFor() * 100) / 100 : 0;
        const r=`<tr><td><select name="items[${idx}][product_id]" class="form-control form-control-sm prod-select"><option value="">Manual</option>${products.map(p=>`<option value="${p.id}" ${(d?.product_id==p.id)?'selected':''} data-price="${p.price}" data-unit="${p.unit}" data-tax="${p.tax!==null?p.tax:''}">${p.name}</option>`).join('')}</select><input type="text" name="items[${idx}][description]" class="form-control form-control-sm mt-1" value="${d?.description||''}" required placeholder="Item description"><input type="text" name="items[${idx}][sub_description]" class="form-control form-control-sm mt-1" value="${d?.sub_description||''}" placeholder="Sub description (e.g. 2x40ft, 1040 bags/container)"></td><td><input type="number" step="1" name="items[${idx}][qty]" class="form-control form-control-sm qty" value="${d?.qty||1}" min="1" required></td><td>${unitInput(`items[${idx}][unit]`, d?.unit||'')}</td><td><input type="number" step="0.01" name="items[${idx}][unit_price]" class="form-control form-control-sm price" data-base-price="${base}" value="${priceInDoc?priceInDoc:(d?.price||0)}" min="0" required></td><td><input type="number" step="0.01" name="items[${idx}][tax_rate]" class="form-control form-control-sm tax" value="${d?.tax!=null?d.tax:''}" min="0" placeholder="Inv. rate"></td><td><input type="number" step="0.01" name="items[${idx}][discount_pct]" class="form-control form-control-sm disc" value="${d?.disc||0}" min="0"></td><td class="lt">0.00</td><td><button type="button" class="btn btn-xs btn-danger rm"><i class="fas fa-times"></i></button></td></tr>`;
        $('#items-table tbody').append(r); idx++; recalc();
    }
    function recalc(){
        const mode = $('#vat-mode').val();
        const rate = parseFloat($('#vat-rate-select').val()==='custom' ? ($('#vat-rate-custom').val()||0) : $('#vat-rate-select').val())||0;
        let s=0,t=0;
        $('#items-table tbody tr').each(function(){
            const $r=$(this);
            const q=parseFloat($r.find('.qty').val())||0,p=parseFloat($r.find('.price').val())||0,
                  tr=$r.find('.tax').val()!==''?parseFloat($r.find('.tax').val()):null,
                  d=parseFloat($r.find('.disc').val())||0;
            const b=q*p, ad=b-b*d/100;
            const r2 = tr!==null ? tr : rate;
            let lt;
            if(mode==='included'){ lt = ad*r2/(100+r2); }
            else { lt = (mode==='none'?0:ad*r2/100); }
            $r.find('.lt').text((mode==='included'?ad:ad+lt).toFixed(2));
            s+=ad; t+=lt;
        });
        $('#subtotal').text((mode==='included'?s-t:s).toFixed(2));
        $('#tax-total').text(t.toFixed(2));
        const disc=parseFloat($('#discount-input').val())||0;
        $('#grand-total').text((s+(mode==='included'?0:t)-disc).toFixed(2));
    }
    $('#add-item').click(()=>addRow());
    $(document).on('click','.rm',function(){$(this).closest('tr').remove();recalc();});
    $(document).on('change keyup','.qty,.price,.tax,.disc,#discount-input',recalc);
    $(document).on('change','#vat-mode,#vat-rate-select,#vat-rate-custom',recalc);
    $(document).on('change','.prod-select',function(){const $r=$(this).closest('tr'),o=$(this).find(':selected');const base=parseFloat(o.data('price'))||0;$r.find('.price').val(base?conv(base):0).attr('data-base-price',base);if(o.text()!=='Manual'){$r.find('input[name$="[description]"]').val(o.text());}$r.find('.tax').val(o.data('tax')!==''?o.data('tax'):'');$r.find('.unit-select').val('');$r.find('.unit-custom').addClass('d-none').val('').prop('disabled',true);$r.find('.unit-select').prop('disabled',false);recalc();});
    $(document).on('change','.unit-select',function(){
        const $u=$(this), $r=$u.closest('tr'), custom=$r.find('.unit-custom');
        if($u.val()==='__other__'){
            $u.prop('disabled',true);
            custom.removeClass('d-none').prop('disabled',false).focus();
        } else {
            $u.prop('disabled',false);
            custom.addClass('d-none').val('').prop('disabled',true);
        }
    });
    $('#vat-rate-select').on('change',function(){
        $('#vat-rate-custom').toggleClass('d-none', $(this).val()!=='custom');
    });
    $('#payment-terms-select').on('change',function(){
        $('#payment-terms-custom').toggleClass('d-none', $(this).val()!=='Custom');
    });
    $('#delivery-terms-select').on('change',function(){
        $('#delivery-terms-custom').toggleClass('d-none', $(this).val()!=='Custom');
    });
    var defaultTermsByType = @json($defaultTermsByType);
    $('#invoice-type-select').on('change', function () {
        var def = defaultTermsByType[$(this).val()] || '';
        var $sel = $('#payment-terms-select');
        if (def && (!$sel.val() || !$sel.find('option:selected').text())) { $sel.val(def); }
    });
    existing.length?existing.forEach(d=>addRow(d)):addRow();

    $('select[name="currency_id"]').on('change', function() {
        const currencyId = $(this).val();
        const $bank = $('#bank-account-select');
        $bank.find('option').each(function() {
            const opt = $(this);
            if (!opt.val()) return;
            if (currencyId && opt.data('currency') != currencyId) { opt.hide(); } else { opt.show(); }
        });
        const visible = $bank.find('option:visible').not('[value=""]');
        if (visible.length === 1) { $bank.val(visible.val()); } else { $bank.val(''); }

        const r = rateFor();
        $('#items-table tbody tr').each(function() {
            const $price = $(this).find('.price');
            const base = parseFloat($price.data('base-price')) || 0;
            $price.val(base ? conv(base) : '');
        });
        recalc();
    });
});
</script>
@endpush
