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
                            <select name="currency_id" class="form-control" id="payment-currency"><option value="">-- Default --</option>@foreach($currencies as $id=>$c)<option value="{{ $id }}">{{ $c }}</option>@endforeach</select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Rate</label>
                            <input type="number" step="0.0001" min="0.0001" name="rate" class="form-control" id="payment-rate" value="1">
                            <small class="text-muted">Units of {{ $defaultCurrencyCode ?? 'this currency' }} per 1 base currency (auto-filled, editable)</small>
                        </div>
                    </div>
                    <div class="form-row">
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
                    <small id="alloc-total-hint" class="text-muted d-none mb-2 d-block"></small>
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
        recalcAllocations();
    });

    let aIdx = 0;
    let invoiceCache = {};
    let autoAmountLocked = false;
    const ratesJson = @json($rates);

    const recalcAllocations = function(){
        let total = 0;
        $('#allocations .alloc-amount').each(function(){
            const v = parseFloat($(this).val());
            if (!isNaN(v) && v > 0) total += v;
        });
        const $amt = $('#payment-amount');
        if (!autoAmountLocked) $amt.val(total > 0 ? total.toFixed(2) : '');
        const paymentAmt = parseFloat($amt.val()) || 0;
        const $hint = $('#alloc-total-hint');
        if (total > 0) {
            $hint.text('Allocated: ' + total.toFixed(2) + ' | Remaining: ' + Math.max(0, paymentAmt - total).toFixed(2)).removeClass('d-none');
        } else {
            $hint.addClass('d-none');
        }
    };

    $('#payment-amount').on('input', function(){
        autoAmountLocked = $(this).val() !== '';
    });

    $('#payment-currency').on('change', function(){
        const r = ratesJson[$(this).val()];
        $('#payment-rate').val(r ? r : '1');
        $('#allocations').empty();
        invoiceCache = {};
        recalcAllocations();
    });
    $('#payment-rate').on('change', function(){
        $('#allocations').empty();
        invoiceCache = {};
        recalcAllocations();
    });
    @if($preselected['type'] === 'supplier')
    $('#payment-type').trigger('change');
    @endif

    const addRow = function(items, kind) {
        const fieldName = kind === 'bill' ? 'supplier_bill_id' : 'invoice_id';
        let opts = '<option value="">-- Select ' + (kind === 'bill' ? 'Bill' : 'Invoice') + ' --</option>';
        items.forEach(function(it) {
            const due = it.due ? ' data-due="' + it.due + '" data-due-label="' + (it.due_label || '') + '"' : '';
            opts += '<option value="' + it.id + '" data-balance="' + it.balance + '"' + due + '>' + it.label + '</option>';
        });
        const r = '<div class="alloc-row mb-2">' +
            '<div class="form-row">' +
            '<div class="form-group col-md-6"><select name="allocations[' + aIdx + '][' + fieldName + ']" class="form-control alloc-invoice">' + opts + '</select></div>' +
            '<div class="form-group col-md-4"><input type="number" step="0.01" name="allocations[' + aIdx + '][amount]" class="form-control alloc-amount" placeholder="Amount" min="0.01"></div>' +
            '<div class="form-group col-md-2"><button type="button" class="btn btn-sm btn-danger rm-alloc"><i class="fas fa-times"></i></button></div>' +
            '</div>' +
            '<small class="alloc-due-hint text-muted d-block px-1 mb-1"></small>' +
            '</div>';
        $('#allocations').append(r);
        aIdx++;
    };

    const fetchAndAdd = function(kind, done) {
        const cur = $('#payment-currency').val() || '';
        const rate = $('#payment-rate').val() || '';
        const url = '{{ route("payments.outstanding-json") }}?type=' + kind + '&currency_id=' + encodeURIComponent(cur) + '&rate=' + encodeURIComponent(rate);
        const cacheKey = kind + '|' + cur + '|' + rate;
        if (invoiceCache[cacheKey]) {
            addRow(invoiceCache[cacheKey], kind);
            if (done) done();
        } else {
            $.get(url, function(data) {
                invoiceCache[cacheKey] = data;
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

    @if($preselected['invoice_id'])
    fetchAndAdd('customer', function(){
        $('#allocations .alloc-invoice').last().val({{ $preselected['invoice_id'] }});
    });
    @endif

    $(document).on('click','.rm-alloc',function(){
        $(this).closest('.alloc-row').remove();
        recalcAllocations();
    });

    $(document).on('change', '.alloc-invoice', function(){
        const $opt = $(this).find(':selected');
        const $row = $(this).closest('.alloc-row');
        const $amt = $row.find('.alloc-amount');
        const due = parseFloat($opt.data('due')) || 0;
        const dueLabel = $opt.data('dueLabel') || '';
        const bal = parseFloat($opt.data('balance')) || 0;
        $amt.val(due > 0 ? due : (bal > 0 ? bal : ''));
        const $hint = $row.find('.alloc-due-hint');
        if (due > 0 && dueLabel) {
            $hint.text('Due per terms: ' + due.toFixed(2) + ' (' + dueLabel + ') · Full balance: ' + bal.toFixed(2));
        } else {
            $hint.text('');
        }
        recalcAllocations();
    });

    $(document).on('input change', '.alloc-amount', function(){
        recalcAllocations();
    });
});
</script>
@endpush
