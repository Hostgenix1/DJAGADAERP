<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'sometimes|max:8|unique:currencies,code',
            'name' => 'sometimes|max:100',
            'symbol' => 'sometimes|max:8',
            'rate' => 'sometimes|numeric',
            'is_active' => 'sometimes|nullable',
        ];
    }
}
