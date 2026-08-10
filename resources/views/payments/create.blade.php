@extends('layouts.app')
@section('title', 'Record Payment')

{{--
  Record Payment - Payments Module
  Module: Payments
  Features: Customer/supplier payment toggle, invoice allocation, dynamic allocation rows, payment method selection, currency, reference/notes fields, amount, date, customer/supplier dropdown
  Version: 1.1.0
--}}

@section('content')
<form method="POST" action="{{ route('payments.store') }}">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-money-check-alt mr-1"></i> Record Payment</h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>Type *</label>
                            <select name="type" class="form-control" id="payment-type" required>
                                <option value="customer" {{ $preselected['type'] === 'supplier' ? '' : 'selected' }}>Customer Payment</option>
                                <option value="supplier" {{ $preselected['type'] === 'supplier' ? 'selected' : '' }}>Supplier Payment</option>
                            </select>
                        </div>
                        <div class="form-group col-md-5" id="customer-field" style="{{ $preselected['type'] === 'supplier' ? 'display:none' : '' }}">
                            <label>Customer *</label>
                            <select name="customer_id" class="form-control">
                                <option value="">-- Select --</option>
                                @foreach($customers as $id=>$n)<option value="{{ $id }}" {{ (string)$id === (string)$preselected['customer_id'] ? 'selected' : '' }}>{{ $n }}</option>@endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-5" id="supplier-field" style="{{ $preselected['type'] === 'supplier' ? '' : 'display:none' }}">
                            <label>Supplier *</label>
                            <select name="supplier_id" class="form-control">
                                <option value="">-- Select --</option>
                                @foreach($suppliers as $id=>$n)<option value="{{ $id }}" {{ (string)$id === (string)$preselected['supplier_id'] ? 'selected' : '' }}>{{ $n }}</option>@endforeach
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

                    <h5 class="mt-4">Allocate to {{ $preselected['type'] === 'supplier' ? 'Bills' : 'Invoices' }} (optional)</h5>
                    <div id="allocations"></div>
                    <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="add-alloc"><i class="fas fa-plus"></i> Add Allocation</button>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-block btn-sm"><i class="fas fa-save mr-1"></i> Save Payment</button>
                    <a href="{{ route('payments.index') }}" class="btn btn-default btn-block btn-sm">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
$(function(){
    const isSupplier = function(){ return $('#payment-type').val() === 'supplier'; };

    $('#payment-type').on('change',function(){
        if(isSupplier()){
            $('#customer-field').hide();$('#supplier-field').show();
            $('#allocations').empty();invoiceCache = {};aIdx = 0;
            $('#alloc-header').text('Allocate to Bills (optional)');
        }else{
            $('#customer-field').show();$('#supplier-field').hide();
            $('#allocations').empty();invoiceCache = {};aIdx = 0;
            $('#alloc-header').text('Allocate to Invoices (optional)');
        }
    });

    let aIdx = 0;
    let invoiceCache = {};
    @if($preselected['type'] === 'supplier')
    $('#payment-type').trigger('change');
    @endif

    const addRow = function(items, kind) {
        const fieldName = kind === 'bill' ? 'supplier_bill_id' : 'invoice_id';
        let opts = '<option value="">-- Select ' + (kind === 'bill' ? 'Bill' : 'Invoice') + ' --</option>';
        items.forEach(function(it) {
            opts += '<option value="' + it.id + '" data-balance="' + it.balance + '">' + it.label + '</option>';
        });
        const r = '<div class="form-row mb-2 alloc-row">' +
            '<div class="form-group col-md-6"><select name="allocations[' + aIdx + '][' + fieldName + ']" class="form-control alloc-invoice">' + opts + '</select></div>' +
            '<div class="form-group col-md-4"><input type="number" step="0.01" name="allocations[' + aIdx + '][amount]" class="form-control alloc-amount" placeholder="Amount" min="0.01"></div>' +
            '<div class="form-group col-md-2"><button type="button" class="btn btn-sm btn-danger rm-alloc"><i class="fas fa-times"></i></button></div>' +
            '</div>';
        $('#allocations').append(r);
        aIdx++;
    };

    const fetchAndAdd = function(kind, done) {
        const url = '{{ route("payments.outstanding-json") }}?type=' + kind;
        if (invoiceCache[kind]) {
            addRow(invoiceCache[kind], kind);
            if (done) done();
        } else {
            $.get(url, function(data) {
                invoiceCache[kind] = data;
                addRow(data, kind);
                if (done) done();
            });
        }
    };

    $('#add-alloc').click(function(){
        fetchAndAdd(isSupplier() ? 'supplier' : 'customer');
    });

    @if($preselected['bill_id'])
    fetchAndAdd('supplier', function(){
        $('#allocations .alloc-invoice').last().val({{ $preselected['bill_id'] }});
    });
    @endif

    $(document).on('click','.rm-alloc',function(){$(this).closest('.alloc-row').remove();});
});
</script>
@endpush
