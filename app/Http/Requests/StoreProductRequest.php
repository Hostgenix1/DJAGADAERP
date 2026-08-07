<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku' => 'required|max:50|unique:products,sku',
            'name' => 'required|max:255',
            'brand_id' => 'nullable',
            'category_id' => 'nullable',
            'supplier_id' => 'nullable',
            'buy_price' => 'nullable|numeric|min:0',
            'sell_price' => 'nullable|numeric|min:0',
            'currency_id' => 'nullable',
            'tax_id' => 'nullable',
            'unit' => 'nullable|max:20',
            'pack_qty' => 'nullable|integer|min:0',
            'pack_type' => 'nullable|in:carton,box,unit,pallet',
            'weight_kg' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|max:100',
            'specifications' => 'nullable',
            'certificates' => 'nullable',
            'is_active' => 'nullable',
        ];
    }
}
