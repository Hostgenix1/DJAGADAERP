@extends('layouts.app')
@section('title', 'Edit Supplier Bill')

@section('content')
@php
    $isLump = $supplierBill->items->count() === 1 && (float) $supplierBill->items->first()->qty === 1.0 && !$supplierBill->items->first()->unit;
    $lumpItem = $isLump ? $supplierBill->items->first() : null;
@endphp
<form method="POST" action="{{ route('supplier_bills.update', $supplierBill->id) }}" id="bill-form">
    @csrf
    @method('PUT')

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-file-invoice-dollar mr-1"></i> Edit {{ $supplierBill->number }}</h3>
            <div class="card-tools">
                <a href="{{ route('supplier_bills.index') }}" class="btn btn-default btn-sm mr-1"><i class="fas fa-times mr-1"></i> Cancel</a>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save mr-1"></i> Save Bill</button>
            </div>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Supplier *</label>
                    <select name="supplier_id" class="form-control" id="supplier-select" required>
                        <option value="">-- Select --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" data-currency="{{ $s->currency_id }}" data-default-term="{{ $s->default_payment_term }}" {{ $supplierBill->supplier_id==$s->id?'selected':'' }}>{{ $s->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>Bill Date *</label>
                    <input type="date" name="bill_date" class="form-control" value="{{ $supplierBill->bill_date?->format('Y-m-d') }}" required>
                </div>
                <div class="form-group col-md-2">
                    <label>Due Date</label>
                    <input type="date" name="due_date" class="form-control" value="{{ $supplierBill->due_date?->format('Y-m-d') }}">
                </div>
                <div class="form-group col-md-2">
                    <label>Currency</label>
                    <select name="currency_id" class="form-control" id="currency-select">
                        <option value="">-- Default --</option>
                        @foreach($currencies as $id=>$c)
                            <option value="{{ $id }}" {{ $supplierBill->currency_id==$id?'selected':'' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>Reference No</label>
                    <input type="text" name="reference_no" class="form-control" value="{{ $supplierBill->reference_no }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Payment Terms</label>
                    @php $isCustomTerms = $supplierBill->payment_terms && !in_array($supplierBill->payment_terms, $paymentTerms); @endphp
                    <select name="payment_terms" class="form-control" id="payment-terms-select">
                        <option value="">-- Select --</option>
                        @foreach($paymentTerms as $pt)
                            <option value="{{ $pt }}" {{ $supplierBill->payment_terms === $pt ? 'selected' : '' }}>{{ $pt }}</option>
                        @endforeach
                        <option value="Custom" {{ $isCustomTerms ? 'selected' : '' }}>-- Custom --</option>
                    </select>
                    <textarea name="payment_terms_custom" id="payment-terms-custom" class="form-control mt-1 {{ $isCustomTerms ? '' : 'd-none' }}" rows="2" placeholder="Custom payment terms...">{{ $isCustomTerms ? $supplierBill->payment_terms : '' }}</textarea>
                </div>
                <div class="form-group col-md-4">
                    <label>VAT Mode</label>
                    <select name="vat_mode" class="form-control" id="vat-mode">
                        @foreach(config('invoice.vat_modes') as $k=>$v)
                            <option value="{{ $k }}" {{ $supplierBill->vat_mode===$k?'selected':'' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>VAT Rate</label>
                    <select name="vat_rate" class="form-control" id="vat-rate-select">
                        @foreach($taxes as $tax)
                            <option value="{{ $tax->rate }}" {{ (string)$supplierBill->vat_rate === (string)$tax->rate ? 'selected' : '' }}>{{ $tax->name }} ({{ rtrim(rtrim($tax->rate,'0'),'.') }}%)</option>
                        @endforeach
                        <option value="custom" {{ $supplierBill->vat_rate !== null && !$taxes->contains(fn($t) => (string)$t->rate === (string)$supplierBill->vat_rate) ? 'selected' : '' }}>-- Custom --</option>
                    </select>
                    <input type="number" step="0.001" name="vat_rate_custom" id="vat-rate-custom" class="form-control mt-1 d-none" placeholder="Custom rate %" value="{{ $supplierBill->vat_rate !== null && !$taxes->contains(fn($t) => (string)$t->rate === (string)$supplierBill->vat_rate) ? $supplierBill->vat_rate : '' }}">
                </div>
            </div>

            <div class="card card-outline card-info mb-3">
                <div class="card-header py-2">
                    <h5 class="card-title mb-0"><i class="fas fa-list-alt mr-1"></i> Bill Type</h5>
                    <div class="card-tools">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn {{ $isLump ? 'btn-default' : 'btn-primary' }} js-mode-btn" data-mode="items">Line Items</button>
                            <button type="button" class="btn {{ $isLump ? 'btn-primary' : 'btn-default' }} js-mode-btn" data-mode="lump">Lump-Sum Expense</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="mode-items" class="{{ $isLump ? 'd-none' : '' }}">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" id="items-table">
                                <thead><tr><th style="width:28%">Description</th><th style="width:7%">Qty</th><th style="width:9%">Unit</th><th style="width:13%">Unit Price</th><th style="width:9%">Tax %</th><th style="width:9%">Disc %</th><th style="width:13%">Total</th><th style="width:5%"></th></tr></thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr><td colspan="6" class="text-right"><strong>Subtotal</strong></td><td colspan="2"><strong id="subtotal">0.00</strong></td></tr>
                                <tr><td colspan="6" class="text-right"><strong>Tax</strong></td><td colspan="2"><strong id="tax-total">0.00</strong></td></tr>
                                <tr><td colspan="6" class="text-right"><strong>Discount</strong></td><td colspan="2"><input type="number" step="0.01" name="discount" id="discount-input" class="form-control" style="width:130px" value="{{ $supplierBill->discount }}"></td></tr>
                                <tr><td colspan="6" class="text-right"><strong>Grand Total</strong></td><td colspan="2"><strong id="grand-total">0.00</strong></td></tr>
                                </tfoot>
                            </table>
                        </div>
                        <button type="button" class="btn btn-success btn-sm mt-2" id="add-item"><i class="fas fa-plus mr-1"></i> Add Item</button>
                    </div>

                    <div id="mode-lump" class="{{ $isLump ? '' : 'd-none' }}">
                        <div class="row">
                            <div class="col-md-8">
                                <label>Description / Expense Type</label>
                                <input type="text" id="lump-description" class="form-control" value="{{ $lumpItem?->description }}" placeholder="e.g. Freight charges, Transport, Inspection, Documentation, Customs, Agency fees">
                            </div>
                            <div class="col-md-4">
                                <label>Amount</label>
                                <input type="number" step="0.01" min="0.01" id="lump-amount" class="form-control" value="{{ $lumpItem?->unit_price }}">
                            </div>
                            <div class="col-md-4 mt-2">
                                <label>Tax % (optional)</label>
                                <input type="number" step="0.001" min="0" max="100" id="lump-tax" class="form-control" value="{{ $lumpItem?->tax_rate }}" placeholder="Use document rate if empty">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="3">{{ $supplierBill->notes }}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('supplier_bills.index') }}" class="btn btn-default btn-sm"><i class="fas fa-times mr-1"></i> Cancel</a>
            <button type="submit" class="btn btn-primary btn-sm float-right"><i class="fas fa-save mr-1"></i> Save Bill</button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
@php
$productsJson = $products->map(fn($p)=>['id'=>$p->id,'name'=>$p->name,'price'=>(float)$p->buy_price,'unit'=>$p->unit ?? '','tax'=>$p->tax?->rate !== null ? (float)$p->tax->rate : null])->values();
$ratesJson = $rates->map(fn($r,$id)=>[(int)$id,(float)$r])->values()->toArray();
$curCodesJson = $currencies->map(fn($code,$id)=>[(int)$id,$code])->values()->toArray();
$itemsJson = $supplierBill->items->map(fn($i)=>['description'=>$i->description,'sub_description'=>$i->sub_description,'qty'=>$i->qty,'unit'=>$i->unit,'price'=>$i->unit_price,'tax'=>$i->tax_rate,'disc'=>$i->discount_pct,'product_id'=>$i->product_id])->values();
@endphp
<script>
$(function(){
    const products = @json($productsJson);
    const units = @json($units);
    const rates = Object.fromEntries(@json($ratesJson));
    const curCodes = Object.fromEntries(@json($curCodesJson));
    let idx=0, mode = {{ $isLump ? "'lump'" : "'items'" }};
    const existing = @json($itemsJson);

    function rateFor(){ const id=$('#currency-select').val(); return id&&rates[id]?parseFloat(rates[id]):1; }
    function conv(base){ return Math.round(base*rateFor()*100)/100; }

    function unitInput(name,val){
        const v=val||'', isCustom=v&&!units.includes(v);
        return `<select name="${name}" class="form-control unit-select" ${isCustom?'disabled':''}><option value="">-- Select Unit --</option>${units.map(u=>`<option value="${u}" ${u===v?'selected':''}>${u}</option>`).join('')}<option value="__other__">-- Other --</option></select><input type="text" name="${name}" class="form-control mt-1 unit-custom ${isCustom?'':'d-none'}" ${isCustom?'':'disabled'} value="${v}" placeholder="Custom unit">`;
    }
    function addRow(d){
        const base=d?.price?parseFloat(d.price):0;
        const r=`<tr><td><select name="items[${idx}][product_id]" class="form-control prod-select"><option value="">Manual</option>${products.map(p=>`<option value="${p.id}" data-price="${p.price}" data-unit="${p.unit}" data-tax="${p.tax!==null?p.tax:''}" ${d?.product_id==p.id?'selected':''}>${p.name}</option>`).join('')}</select><input type="text" name="items[${idx}][description]" class="form-control mt-1" value="${d?.description||''}" required placeholder="Item description"><input type="text" name="items[${idx}][sub_description]" class="form-control mt-1" value="${d?.sub_description||''}" placeholder="Sub description"></td><td><input type="number" step="1" name="items[${idx}][qty]" class="form-control qty" value="${d?.qty||1}" min="1" required></td><td>${unitInput(`items[${idx}][unit]`, d?.unit||'')}</td><td><input type="number" step="0.01" name="items[${idx}][unit_price]" class="form-control price" data-base-price="${d?.product_id?0:base}" value="${d?.price||0}" min="0" required></td><td><input type="number" step="0.01" name="items[${idx}][tax_rate]" class="form-control tax" value="${d?.tax!=null?d.tax:''}" min="0" placeholder="Doc rate"></td><td><input type="number" step="0.01" name="items[${idx}][discount_pct]" class="form-control disc" value="${d?.disc||0}" min="0"></td><td class="lt">0.00</td><td><button type="button" class="btn btn-sm btn-danger rm"><i class="fas fa-times"></i></button></td></tr>`;
        $('#items-table tbody').append(r); idx++; recalc();
    }
    function recalc(){
        const modeV=$('#vat-mode').val();
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
            if(modeV==='included'){ lt=ad*r2/(100+r2); } else { lt=(modeV==='none'?0:ad*r2/100); }
            $r.find('.lt').text((modeV==='included'?ad:ad+lt).toFixed(2));
            s+=ad; t+=lt;
        });
        $('#subtotal').text((modeV==='included'?s-t:s).toFixed(2));
        $('#tax-total').text(t.toFixed(2));
        const disc=parseFloat($('#discount-input').val())||0;
        $('#grand-total').text((s+(modeV==='included'?0:t)-disc).toFixed(2));
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
        $r.find('.tax').val(o.data('tax')!==''?o.data('tax'):'');
        recalc();
    });
    $(document).on('change','.unit-select',function(){
        const $u=$(this), $r=$u.closest('tr'), custom=$r.find('.unit-custom');
        if($u.val()==='__other__'){ $u.prop('disabled',true); custom.removeClass('d-none').prop('disabled',false).focus(); }
        else { $u.prop('disabled',false); custom.addClass('d-none').val('').prop('disabled',true); }
    });
    $('#vat-rate-select').on('change',function(){ $('#vat-rate-custom').toggleClass('d-none', $(this).val()!=='custom'); });
    $('#payment-terms-select').on('change',function(){ $('#payment-terms-custom').toggleClass('d-none', $(this).val()!=='Custom'); });

    $('.js-mode-btn').on('click',function(){
        mode=$(this).data('mode');
        $('.js-mode-btn').removeClass('btn-primary').addClass('btn-default');
        $(this).removeClass('btn-default').addClass('btn-primary');
        $('#mode-items').toggleClass('d-none', mode!=='items');
        $('#mode-lump').toggleClass('d-none', mode!=='lump');
    });

    $('#bill-form').on('submit',function(e){
        if(mode==='lump'){
            e.preventDefault();
            const desc=$('#lump-description').val().trim();
            const amount=parseFloat($('#lump-amount').val());
            const tax=$('#lump-tax').val();
            if(!desc){ alert('Please enter a description for the lump-sum expense.'); return false; }
            if(!amount||amount<=0){ alert('Please enter a valid amount.'); return false; }
            const $f=$(this);
            $f.find('input[name^="items["]').remove();
            $('<input>').attr({type:'hidden',name:'items[0][product_id]',value:''}).appendTo($f);
            $('<input>').attr({type:'hidden',name:'items[0][description]',value:desc}).appendTo($f);
            $('<input>').attr({type:'hidden',name:'items[0][sub_description]',value:''}).appendTo($f);
            $('<input>').attr({type:'hidden',name:'items[0][qty]',value:'1'}).appendTo($f);
            $('<input>').attr({type:'hidden',name:'items[0][unit]',value:''}).appendTo($f);
            $('<input>').attr({type:'hidden',name:'items[0][unit_price]',value:amount}).appendTo($f);
            $('<input>').attr({type:'hidden',name:'items[0][tax_rate]',value:tax||''}).appendTo($f);
            $('<input>').attr({type:'hidden',name:'items[0][discount_pct]',value:'0'}).appendTo($f);
            $f.off('submit').submit();
        }
    });

    existing.length?existing.forEach(d=>addRow(d)):addRow();
});
</script>
@endpush
