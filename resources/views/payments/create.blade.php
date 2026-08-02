@extends('layouts.app')
@section('title', 'Record Payment')
@section('content')
<form method="POST" action="{{ route('payments.store') }}">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title">Record Payment</h3></div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>Type *</label>
                            <select name="type" class="form-control" id="payment-type" required>
                                <option value="customer">Customer Payment</option>
                                <option value="supplier">Supplier Payment</option>
                            </select>
                        </div>
                        <div class="form-group col-md-5" id="customer-field">
                            <label>Customer *</label>
                            <select name="customer_id" class="form-control">
                                <option value="">-- Select --</option>
                                @foreach($customers as $id=>$n)<option value="{{ $id }}">{{ $n }}</option>@endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-5" id="supplier-field" style="display:none">
                            <label>Supplier *</label>
                            <select name="supplier_id" class="form-control">
                                <option value="">-- Select --</option>
                                @foreach($suppliers as $id=>$n)<option value="{{ $id }}">{{ $n }}</option>@endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Method *</label>
                            <select name="method" class="form-control" required>
                                @foreach($methods as $m)<option value="{{ $m }}">{{ ucfirst($m) }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Amount *</label>
                            <input type="number" step="0.01" name="amount" class="form-control" id="payment-amount" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Currency</label>
                            <select name="currency_id" class="form-control"><option value="">-- Default --</option>@foreach($currencies as $id=>$c)<option value="{{ $id }}">{{ $c }}</option>@endforeach</select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Date *</label>
                            <input type="date" name="paid_on" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Reference</label>
                        <input type="text" name="reference" class="form-control" placeholder="Cheque #, transaction ID, etc.">
                    </div>
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>

                    <h5 class="mt-4">Allocate to Invoices (optional)</h5>
                    <div id="allocations"></div>
                    <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="add-alloc"><i class="fas fa-plus"></i> Add Allocation</button>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card"><div class="card-body">
                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save"></i> Save Payment</button>
                <a href="{{ route('payments.index') }}" class="btn btn-default btn-block">Cancel</a>
            </div></div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
$(function(){
    $('#payment-type').on('change',function(){
        if($(this).val()==='customer'){$('#customer-field').show();$('#supplier-field').hide();}else{$('#customer-field').hide();$('#supplier-field').show();}
    });
    let aIdx=0;
    $('#add-alloc').click(function(){
        const r=`<div class="form-row mb-2 alloc-row">
            <div class="form-group col-md-6"><select name="allocations[${aIdx}][invoice_id]" class="form-control"><option value="">-- Invoice --</option></select></div>
            <div class="form-group col-md-4"><input type="number" step="0.01" name="allocations[${aIdx}][amount]" class="form-control" placeholder="Amount" min="0.01"></div>
            <div class="form-group col-md-2"><button type="button" class="btn btn-sm btn-danger rm-alloc"><i class="fas fa-times"></i></button></div>
        </div>`;
        $('#allocations').append(r);
        aIdx++;
    });
    $(document).on('click','.rm-alloc',function(){$(this).closest('.alloc-row').remove();});
});
</script>
@endpush
