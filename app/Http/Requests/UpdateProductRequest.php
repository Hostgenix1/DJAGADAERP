<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku' => 'sometimes|max:50|unique:products,sku',
            'name' => 'sometimes|max:255',
            'brand_id' => 'sometimes|nullable',
            'category_id' => 'sometimes|nullable',
            'supplier_id' => 'sometimes|nullable',
            'buy_price' => 'sometimes|numeric',
            'sell_price' => 'sometimes|numeric',
            'currency_id' => 'sometimes|nullable',
            'tax_id' => 'sometimes|nullable',
            'unit' => 'sometimes|max:20',
            'pack_qty' => 'sometimes|nullable',
            'weight_kg' => 'sometimes|nullable',
            'dimensions' => 'sometimes|max:100',
            'specifications' => 'sometimes|nullable',
            'certificates' => 'sometimes|nullable',
            'is_active' => 'sometimes|nullable',
        ];
    }
}
