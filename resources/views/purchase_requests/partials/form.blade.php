@php
    $purchaseRequest = $purchaseRequest ?? null;
    $editing = $purchaseRequest !== null;

    if (! isset($rows)) {
        $rows = old('items', $editing
            ? $purchaseRequest?->items->map(fn ($item) => [
                'product_id' => $item->product_id,
                'description' => $item->description,
                'qty' => $item->qty,
                'unit' => $item->unit,
                'unit_price' => $item->unit_price,
            ])->all()
            : []);
    }

    if (empty($rows)) {
        $rows = [['product_id' => '', 'description' => '', 'qty' => 1, 'unit' => '', 'unit_price' => 0]];
    }

    $rows = array_values($rows);
    $currencyCode = $currencies->get(old('currency_id', $purchaseRequest?->currency_id ?? array_key_first($currencies->all())));
@endphp

<form method="POST"
      action="{{ $editing ? route('purchase_requests.update', $purchaseRequest) : route('purchase_requests.store') }}">
    @csrf
    @if($editing)
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-primary card-outline">
                <div class="card-header"><h3 class="card-title"><i class="fas fa-clipboard-check mr-1"></i> Request Details</h3></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="supplier_id">Preferred Supplier</label>
                                <select name="supplier_id" id="supplier_id" class="form-control">
                                    <option value="">-- Select supplier (required to convert to PO) --</option>
                                    @foreach($suppliers as $id => $name)
                                        <option value="{{ $id }}" {{ (string) old('supplier_id', $purchaseRequest?->supplier_id ?? '') === (string) $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="currency_id">Currency</label>
                                <select name="currency_id" id="currency_id" class="form-control">
                                    <option value="">-- Default --</option>
                                    @foreach($currencies as $id => $code)
                                        <option value="{{ $id }}" {{ (string) old('currency_id', $purchaseRequest?->currency_id ?? '') === (string) $id ? 'selected' : '' }}>{{ $code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="department">Department</label>
                                <input type="text" name="department" id="department" class="form-control" maxlength="100"
                                       value="{{ old('department', $purchaseRequest?->department ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="priority">Priority *</label>
                                <select name="priority" id="priority" class="form-control" required>
                                    @foreach(['low', 'normal', 'high', 'urgent'] as $priority)
                                        <option value="{{ $priority }}" {{ old('priority', $purchaseRequest?->priority ?? 'normal') === $priority ? 'selected' : '' }}>{{ ucfirst($priority) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="needed_by">Needed By</label>
                                <input type="date" name="needed_by" id="needed_by" class="form-control"
                                       value="{{ old('needed_by', $purchaseRequest?->needed_by?->toDateString() ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="justification">Justification</label>
                                <textarea name="justification" id="justification" rows="2" class="form-control" maxlength="2000">{{ old('justification', $purchaseRequest?->justification ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-0">
                                <label for="notes">Notes</label>
                                <textarea name="notes" id="notes" rows="2" class="form-control" maxlength="2000">{{ old('notes', $purchaseRequest?->notes ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list mr-1"></i> Items</h3>
                    <div class="card-tools">
                        <button type="button" id="add-item" class="btn btn-sm btn-primary"><i class="fas fa-plus mr-1"></i> Add Item</button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Description *</th>
                                <th>Qty *</th>
                                <th>Unit</th>
                                <th>Unit Price *</th>
                                <th class="text-right">Line Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="items-body">
                            @foreach($rows as $index => $row)
                                @include('purchase_requests.partials.item-row', ['index' => $index, 'item' => $row, 'products' => $products, 'units' => $units])
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <strong>Estimated Total: <span id="items-total">0.00</span> {{ $currencyCode }}</strong>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save mr-1"></i> {{ $editing ? 'Save Changes' : 'Create Request' }}</button>
                    <a href="{{ $editing ? route('purchase_requests.show', $purchaseRequest) : route('purchase_requests.index') }}" class="btn btn-default btn-block">Cancel</a>
                    <p class="text-muted small mt-3 mb-0">Requests start as drafts. Submit for approval, then convert an approved request into a purchase order.</p>
                </div>
            </div>
        </div>
    </div>
</form>

<datalist id="units-list">
    @foreach($units as $unit)
        <option value="{{ $unit }}"></option>
    @endforeach
</datalist>

<script type="text/template" id="item-row-template">
    @include('purchase_requests.partials.item-row', ['index' => '__INDEX__', 'item' => [], 'products' => $products, 'units' => $units])
</script>

@push('scripts')
<script>
$(function () {
    let itemIndex = {{ count($rows) }};

    function recalcRow($row) {
        const qty = parseFloat($row.find('.qty').val()) || 0;
        const price = parseFloat($row.find('.unit-price').val()) || 0;
        $row.find('.line-total').text((qty * price).toFixed(2));
    }

    function recalcTotal() {
        let total = 0;
        $('.item-row').each(function () {
            const qty = parseFloat($(this).find('.qty').val()) || 0;
            const price = parseFloat($(this).find('.unit-price').val()) || 0;
            total += qty * price;
        });
        $('#items-total').text(total.toFixed(2));
    }

    $(document).on('input', '.qty, .unit-price', function () {
        recalcRow($(this).closest('tr'));
        recalcTotal();
    });

    $(document).on('change', '.product-select', function () {
        const $row = $(this).closest('tr');
        const $option = $(this).find('option:selected');

        if (! this.value) {
            return;
        }

        $row.find('.description').val($option.data('name') || '');
        $row.find('.unit').val($option.data('unit') || '');
        $row.find('.unit-price').val($option.data('price') || 0);
        recalcRow($row);
        recalcTotal();
    });

    $(document).on('click', '.remove-row', function () {
        if ($('.item-row').length > 1) {
            $(this).closest('tr').remove();
            recalcTotal();
        }
    });

    $('#add-item').on('click', function () {
        const html = $('#item-row-template').html().replace(/__INDEX__/g, itemIndex++);
        $('#items-body').append(html);
        recalcTotal();
    });

    $('.item-row').each(function () {
        recalcRow($(this));
    });
    recalcTotal();
});
</script>
@endpush
