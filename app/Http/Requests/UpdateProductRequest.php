<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'sku' => ['sometimes', 'max:50', Rule::unique('products', 'sku')->ignore($productId)],
            'name' => 'sometimes|max:255',
            'brand_id' => 'sometimes|nullable',
            'category_id' => 'sometimes|nullable',
            'supplier_id' => 'sometimes|nullable',
            'buy_price' => 'sometimes|numeric|min:0',
            'sell_price' => 'sometimes|numeric|min:0',
            'currency_id' => 'sometimes|nullable',
            'tax_id' => 'sometimes|nullable',
            'unit' => 'sometimes|max:20',
            'pack_qty' => 'sometimes|nullable|integer|min:0',
            'pack_type' => 'sometimes|nullable|in:carton,box,unit,pallet',
            'weight_kg' => 'sometimes|nullable|numeric|min:0',
            'dimensions' => 'sometimes|max:100',
            'specifications' => 'sometimes|nullable',
            'certificates' => 'sometimes|nullable',
            'is_active' => 'sometimes|nullable',
        ];
    }
}
