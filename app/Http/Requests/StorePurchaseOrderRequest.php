<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'po_date' => 'required|date',
            'expected_delivery' => 'nullable|date|after_or_equal:po_date',
            'payment_terms' => 'nullable|string|max:1000',
            'payment_terms_custom' => 'nullable|string|max:1000',
            'delivery_terms' => 'nullable|string|max:500',
            'delivery_terms_custom' => 'nullable|string|max:500',
            'port_of_loading' => 'nullable|string|max:500',
            'port_of_discharge' => 'nullable|string|max:500',
            'goods_origin' => 'nullable|string|max:500',
            'reference_no' => 'nullable|string|max:100',
            'vat_mode' => 'required|in:none,excluded,included',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string|max:1000',
            'discount' => ['nullable', 'numeric', 'min:0'],
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.sub_description' => 'nullable|string|max:255',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.unit' => 'nullable|string|max:20',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'items.*.discount_pct' => 'nullable|numeric|min:0|max:100',
        ];
    }
}
