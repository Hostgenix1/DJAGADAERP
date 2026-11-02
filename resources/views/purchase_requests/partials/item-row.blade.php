@php
    $item = $item ?? [];
@endphp
<tr class="item-row">
    <td style="min-width:160px">
        <select name="items[{{ $index }}][product_id]" class="form-control form-control-sm product-select">
            <option value="">-- Custom item --</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}"
                        data-name="{{ e($product->name) }}"
                        data-unit="{{ e($product->unit) }}"
                        data-price="{{ $product->buy_price }}"
                        {{ (string) old('items.'.$index.'.product_id', $item['product_id'] ?? '') === (string) $product->id ? 'selected' : '' }}>
                    {{ $product->name }}
                </option>
            @endforeach
        </select>
    </td>
    <td style="min-width:180px">
        <input type="text" name="items[{{ $index }}][description]" class="form-control form-control-sm description"
               value="{{ old('items.'.$index.'.description', $item['description'] ?? '') }}" required>
    </td>
    <td style="width:90px">
        <input type="number" step="0.01" min="0.01" name="items[{{ $index }}][qty]" class="form-control form-control-sm qty"
               value="{{ old('items.'.$index.'.qty', $item['qty'] ?? 1) }}" required>
    </td>
    <td style="width:90px">
        <input type="text" name="items[{{ $index }}][unit]" class="form-control form-control-sm unit" list="units-list"
               value="{{ old('items.'.$index.'.unit', $item['unit'] ?? '') }}">
    </td>
    <td style="width:120px">
        <input type="number" step="0.01" min="0" name="items[{{ $index }}][unit_price]" class="form-control form-control-sm unit-price"
               value="{{ old('items.'.$index.'.unit_price', $item['unit_price'] ?? 0) }}" required>
    </td>
    <td class="text-right align-middle"><span class="line-total">0.00</span></td>
    <td style="width:45px">
        <button type="button" class="btn btn-sm btn-outline-danger remove-row" title="Remove"><i class="fas fa-times"></i></button>
    </td>
</tr>
