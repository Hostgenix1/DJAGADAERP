@extends('layouts.app')
@section('title', 'Edit Purchase Order')

@section('content')
<form method="POST" action="{{ route('purchase_orders.update', $purchaseOrder->id) }}">
    @csrf
    @method('PUT')

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clipboard-list mr-1"></i> Edit PO {{ $purchaseOrder->number }}</h3>
            <div class="card-tools">
                <a href="{{ route('purchase_orders.index') }}" class="btn btn-default btn-sm mr-1"><i class="fas fa-times mr-1"></i> Cancel</a>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save mr-1"></i> Save PO</button>
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Supplier *</label>
                    <select name="supplier_id" class="form-control" id="supplier-select" required>
                        <option value="">-- Select --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" data-currency="{{ $s->currency_id }}" data-default-term="{{ $s->default_payment_term }}" {{ $purchaseOrder->supplier_id==$s->id?'selected':'' }}>{{ $s->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>PO Date *</label>
                    <input type="date" name="po_date" class="form-control" value="{{ $purchaseOrder->po_date?->format('Y-m-d') }}" required>
                </div>
                <div class="form-group col-md-3">
                    <label>Expected Delivery</label>
                    <input type="date" name="expected_delivery" class="form-control" value="{{ $purchaseOrder->expected_delivery?->format('Y-m-d') }}">
                </div>
                <div class="form-group col-md-3">
                    <label>Reference No</label>
                    <input type="text" name="reference_no" class="form-control" value="{{ $purchaseOrder->reference_no }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>Currency</label>
                    <select name="currency_id" class="form-control" id="currency-select">
                        <option value="">-- Default --</option>
                        @foreach($currencies as $id=>$c)
                            <option value="{{ $id }}" {{ $purchaseOrder->currency_id==$id?'selected':'' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label>Incoterm / Delivery Terms</label>
                    @php
                        $dt = old('delivery_terms', $purchaseOrder->delivery_terms);
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
                <div class="form-group col-md-3">
                    <label>Goods Origin</label>
                    <input type="text" name="goods_origin" class="form-control" value="{{ $purchaseOrder->goods_origin }}">
                </div>
                <div class="form-group col-md-3">
                    <label>Payment Terms</label>
                    @php $isCustomTerms = $purchaseOrder->payment_terms && !in_array($purchaseOrder->payment_terms, $paymentTerms); @endphp
                    <select name="payment_terms" class="form-control" id="payment-terms-select">
                        <option value="">-- Select --</option>
                        @foreach($paymentTerms as $pt)
                            <option value="{{ $pt }}" {{ $purchaseOrder->payment_terms === $pt ? 'selected' : '' }}>{{ $pt }}</option>
                        @endforeach
                        <option value="Custom" {{ $isCustomTerms ? 'selected' : '' }}>-- Custom --</option>
                    </select>
                    <textarea name="payment_terms_custom" id="payment-terms-custom" class="form-control mt-1 {{ $isCustomTerms ? '' : 'd-none' }}" rows="2" placeholder="Custom payment terms...">{{ $isCustomTerms ? $purchaseOrder->payment_terms : '' }}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Port of Loading</label>
                    <input type="text" name="port_of_loading" class="form-control" value="{{ $purchaseOrder->port_of_loading }}">
                </div>
                <div class="form-group col-md-6">
                    <label>Port of Discharge</label>
                    <input type="text" name="port_of_discharge" class="form-control" value="{{ $purchaseOrder->port_of_discharge }}">
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
                                    <option value="{{ $k }}" {{ $purchaseOrder->vat_mode===$k?'selected':'' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4 mb-0">
                            <label>VAT Rate</label>
                            <select name="vat_rate" class="form-control" id="vat-rate-select">
                                @foreach($taxes as $tax)
                                    <option value="{{ $tax->rate }}" {{ (string)$purchaseOrder->vat_rate === (string)$tax->rate ? 'selected' : '' }}>{{ $tax->name }} ({{ rtrim(rtrim($tax->rate,'0'),'.') }}%)</option>
                                @endforeach
                                <option value="custom" {{ $purchaseOrder->vat_rate !== null && !$taxes->contains(fn($t) => (string)$t->rate === (string)$purchaseOrder->vat_rate) ? 'selected' : '' }}>-- Custom --</option>
                            </select>
                            <input type="number" step="0.001" name="vat_rate_custom" id="vat-rate-custom" class="form-control mt-1 d-none" placeholder="Custom rate %" value="{{ $purchaseOrder->vat_rate !== null && !$taxes->contains(fn($t) => (string)$t->rate === (string)$purchaseOrder->vat_rate) ? $purchaseOrder->vat_rate : '' }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-info mb-3">
                <div class="card-header py-2">
                    <h5 class="card-title mb-0"><i class="fas fa-list-alt mr-1"></i> Line Items</h5>
                    <div class="card-tools">
                        <button type="button" class="btn btn-success btn-sm" id="add-item"><i class="fas fa-plus mr-1"></i> Add Item</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0" id="items-table">
                            <thead><tr><th style="width:28%">Description</th><th style="width:7%">Qty</th><th style="width:9%">Unit</th><th style="width:13%">Unit Price</th><th style="width:9%">Tax %</th><th style="width:9%">Disc %</th><th style="width:13%">Total</th><th style="width:5%"></th></tr></thead>
                            <tbody></tbody>
                            <tfoot>
                            <tr><td colspan="6" class="text-right"><strong>Subtotal</strong></td><td colspan="2"><strong id="subtotal">0.00</strong></td></tr>
                            <tr><td colspan="6" class="text-right"><strong>Tax</strong></td><td colspan="2"><strong id="tax-total">0.00</strong></td></tr>
                            <tr><td colspan="6" class="text-right"><strong>Discount</strong></td><td colspan="2"><input type="number" step="0.01" name="discount" id="discount-input" class="form-control" style="width:130px" value="{{ $purchaseOrder->discount }}"></td></tr>
                            <tr><td colspan="6" class="text-right"><strong>Grand Total</strong></td><td colspan="2"><strong id="grand-total">0.00</strong></td></tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="3">{{ $purchaseOrder->notes }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('purchase_orders.index') }}" class="btn btn-default btn-sm"><i class="fas fa-times mr-1"></i> Cancel</a>
            <button type="submit" class="btn btn-primary btn-sm float-right"><i class="fas fa-save mr-1"></i> Save PO</button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
@php
$productsJson = $products->map(fn($p)=>['id'=>$p->id,'name'=>$p->name,'price'=>(float)$p->buy_price,'unit'=>$p->unit ?? '' ])->values();
$ratesJson = $rates->map(fn($r,$id)=>[(int)$id,(float)$r])->values()->toArray();
$curCodesJson = $currencies->map(fn($code,$id)=>[(int)$id,$code])->values()->toArray();
$itemsJson = $purchaseOrder->items->map(fn($i)=>['description'=>$i->description,'sub_description'=>$i->sub_description,'qty'=>$i->qty,'unit'=>$i->unit,'price'=>$i->unit_price,'tax'=>$i->tax_rate,'disc'=>$i->discount_pct,'product_id'=>$i->product_id])->values();
@endphp
<script>
$(function(){
    const products = @json($productsJson);
    const units = @json($units);
    const rates = Object.fromEntries(@json($ratesJson));
    const curCodes = Object.fromEntries(@json($curCodesJson));
    let idx=0;
    const existing = @json($itemsJson);

    function rateFor(){ const id=$('#currency-select').val(); return id&&rates[id]?parseFloat(rates[id]):1; }
    function conv(base){ return Math.round(base*rateFor()*100)/100; }

    function unitInput(name,val){
        const v=val||'', isCustom=v&&!units.includes(v);
        return `<select name="${name}" class="form-control unit-select" ${isCustom?'disabled':''}><option value="">-- Select Unit --</option>${units.map(u=>`<option value="${u}" ${u===v?'selected':''}>${u}</option>`).join('')}<option value="__other__">-- Other --</option></select><input type="text" name="${name}" class="form-control mt-1 unit-custom ${isCustom?'':'d-none'}" ${isCustom?'':'disabled'} value="${v}" placeholder="Custom unit">`;
    }
    function addRow(d){
        const base=d?.price?parseFloat(d.price):0;
        const r=`<tr><td><select name="items[${idx}][product_id]" class="form-control prod-select"><option value="">Manual</option>${products.map(p=>`<option value="${p.id}" data-price="${p.price}" data-unit="${p.unit}" ${d?.product_id==p.id?'selected':''}>${p.name}</option>`).join('')}</select><input type="text" name="items[${idx}][description]" class="form-control mt-1" value="${d?.description||''}" required placeholder="Item description"><input type="text" name="items[${idx}][sub_description]" class="form-control mt-1" value="${d?.sub_description||''}" placeholder="Sub description"></td><td><input type="number" step="0.01" name="items[${idx}][qty]" class="form-control qty" value="${d?.qty||1}" min="0.01" required></td><td>${unitInput(`items[${idx}][unit]`, d?.unit||'')}</td><td><input type="number" step="0.01" name="items[${idx}][unit_price]" class="form-control price" data-base-price="${d?.product_id?0:base}" value="${d?.price||0}" min="0" required></td><td><input type="number" step="0.01" name="items[${idx}][tax_rate]" class="form-control tax" value="${d?.tax!=null?d.tax:''}" min="0" placeholder="Doc rate"></td><td><input type="number" step="0.01" name="items[${idx}][discount_pct]" class="form-control disc" value="${d?.disc||0}" min="0"></td><td class="lt">0.00</td><td><button type="button" class="btn btn-sm btn-danger rm"><i class="fas fa-times"></i></button></td></tr>`;
        $('#items-table tbody').append(r); idx++; recalc();
    }
    function recalc(){
        const mode=$('#vat-mode').val();
        const rate=parseFloat($('#vat-rate-select').val()==='custom'?($('#vat-rate-custom').val()||0):$('#vat-rate-select').val())||0;
        let s=0,t=0;
        $('#items-table tbody tr').each(function(){
            const $r=$(this);
            const q=parseFloat($r.find('.qty').val())||0, p=parseFloat($r.find('.price').val())||0,
                  tr=$r.find('.tax').val()!==''?parseFloat($r.find('.tax').val()):null,
                  d=parseFloat($r.find('.disc').val())||0;
            const b=q*p, ad=b-b*d/100;
            const r2=(tr!==null&&tr!=='')?tr:rate;
            let lt;
            if(mode==='included'){ lt=ad*r2/(100+r2); } else { lt=(mode==='none'?0:ad*r2/100); }
            $r.find('.lt').text((mode==='included'?ad:ad+lt).toFixed(2));
            s+=ad; t+=lt;
        });
        $('#subtotal').text((mode==='included'?s-t:s).toFixed(2));
        $('#tax-total').text(t.toFixed(2));
        const disc=parseFloat($('#discount-input').val())||0;
        $('#grand-total').text((s-t+(mode==='included'?0:t)-disc).toFixed(2));
    }
    $('#add-item').click(()=>addRow());
    $(document).on('click','.rm',function(){$(this).closest('tr').remove();recalc();});
    $(document).on('change keyup','.qty,.price,.tax,.disc,#discount-input',recalc);
    $(document).on('change','#vat-mode,#vat-rate-select,#vat-rate-custom',recalc);
    $(document).on('change','.prod-select',function(){
        const $r=$(this).closest('tr'), o=$(this).find(':selected');
        const base=parseFloat(o.data('price'))||0;
        if(base){ $r.find('.price').val(conv(base)).attr('data-base-price',base); }
        if(o.text()!=='Manual'){ $r.find('input[name$="[description]"]').val(o.text()); }
        recalc();
    });
    $(document).on('change','.unit-select',function(){
        const $u=$(this), $r=$u.closest('tr'), custom=$r.find('.unit-custom');
        if($u.val()==='__other__'){ $u.prop('disabled',true); custom.removeClass('d-none').prop('disabled',false).focus(); }
        else { $u.prop('disabled',false); custom.addClass('d-none').val('').prop('disabled',true); }
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
    existing.length?existing.forEach(d=>addRow(d)):addRow();
});
</script>
@endpush
