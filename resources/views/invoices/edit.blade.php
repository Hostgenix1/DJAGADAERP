@extends('layouts.app')
@section('title', 'Edit '.$invoice->number)

{{--
  Edit Invoice - Invoices Module
  Module: Invoices
  Features: Dynamic line items with existing data, product auto-fill, auto-calculate totals, invoice type/customer/date/currency, PUT method override, notes/terms
  Version: 1.0.0
--}}

@section('content')
<form method="POST" action="{{ route('invoices.update', $invoice) }}">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <div class="card" style="border:none; box-shadow: 0 0 15px rgba(0,0,0,.04); border-radius: 12px;">
                <div class="card-header border-0 pt-4 pb-2" style="border-bottom: 1px solid #f0f0f0 !important;">
                    <h5 class="font-weight-bold text-dark mb-0" style="font-size:1.1rem;">
                        <i class="fas fa-file-invoice-dollar mr-2 text-primary"></i>Edit {{ $invoice->number }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>Type *</label>
                            <select name="type" class="form-control" required>
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
                    <div class="form-group col-md-4 px-0">
                        <label>Currency</label>
                        <select name="currency_id" class="form-control"><option value="">-- Default --</option>@foreach($currencies as $id=>$c)<option value="{{ $id }}" {{ $invoice->currency_id==$id?'selected':'' }}>{{ $c }}</option>@endforeach</select>
                    </div>
                    <h5>Line Items</h5>
                    <table class="table table-sm" id="items-table">
                        <thead><tr><th style="width:30%">Description</th><th>Qty</th><th>Unit</th><th>Price</th><th>Tax %</th><th>Disc %</th><th>Total</th><th></th></tr></thead>
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
            <div class="card" style="border:none; box-shadow: 0 0 15px rgba(0,0,0,.04); border-radius: 12px;">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-block btn-sm"><i class="fas fa-save mr-1"></i> Update</button>
                    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-outline-secondary btn-block btn-sm">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
@php
$productsJson = $products->map(fn($p)=>['id'=>$p->id,'name'=>$p->name,'price'=>(float)$p->sell_price,'unit'=>$p->unit?:'pc'])->values();
$itemsJson = $invoice->items->map(fn($i)=>['product_id'=>$i->product_id,'description'=>$i->description,'qty'=>$i->qty,'unit'=>$i->unit,'price'=>$i->unit_price,'tax'=>$i->tax_rate,'disc'=>$i->discount_pct])->values();
@endphp
@endsection

@push('scripts')
<script>
$(function(){
    const products = {!! $productsJson !!};
    const existing = {!! $itemsJson !!};
    let idx=0;
    function addRow(d){
        const r=`<tr><td><select name="items[${idx}][product_id]" class="form-control form-control-sm prod-select"><option value="">Manual</option>${products.map(p=>`<option value="${p.id}" ${(d?.product_id==p.id)?'selected':''} data-price="${p.price}" data-unit="${p.unit}">${p.name}</option>`).join('')}</select><input type="text" name="items[${idx}][description]" class="form-control form-control-sm mt-1" value="${d?.description||''}" required></td><td><input type="number" step="0.01" name="items[${idx}][qty]" class="form-control form-control-sm qty" value="${d?.qty||1}" min="0.01" required></td><td><input type="text" name="items[${idx}][unit]" class="form-control form-control-sm unit" value="${d?.unit||'pc'}"></td><td><input type="number" step="0.01" name="items[${idx}][unit_price]" class="form-control form-control-sm price" value="${d?.price||0}" min="0" required></td><td><input type="number" step="0.01" name="items[${idx}][tax_rate]" class="form-control form-control-sm tax" value="${d?.tax||0}" min="0"></td><td><input type="number" step="0.01" name="items[${idx}][discount_pct]" class="form-control form-control-sm disc" value="${d?.disc||0}" min="0"></td><td class="lt">0.00</td><td><button type="button" class="btn btn-xs btn-danger rm"><i class="fas fa-times"></i></button></td></tr>`;
        $('#items-table tbody').append(r); idx++; recalc();
    }
    function recalc(){let s=0,t=0;$('#items-table tbody tr').each(function(){const $r=$(this);const q=parseFloat($r.find('.qty').val())||0,p=parseFloat($r.find('.price').val())||0,tr=parseFloat($r.find('.tax').val())||0,d=parseFloat($r.find('.disc').val())||0;const b=q*p,ad=b-b*d/100,lt=ad*tr/100;$r.find('.lt').text((ad+lt).toFixed(2));s+=ad;t+=lt;});$('#subtotal').text(s.toFixed(2));$('#tax-total').text(t.toFixed(2));$('#grand-total').text((s+t-(parseFloat($('#discount-input').val())||0)).toFixed(2));}
    $('#add-item').click(()=>addRow());
    $(document).on('click','.rm',function(){$(this).closest('tr').remove();recalc();});
    $(document).on('change keyup','.qty,.price,.tax,.disc,#discount-input',recalc);
    $(document).on('change','.prod-select',function(){const $r=$(this).closest('tr'),o=$(this).find(':selected');$r.find('.price').val(o.data('price')||0);$r.find('.unit').val(o.data('unit')||'pc');if(o.text()!=='Manual')$r.find('input[name$="[description]"]').val(o.text());recalc();});
    existing.length?existing.forEach(d=>addRow(d)):addRow();
});
</script>
@endpush
