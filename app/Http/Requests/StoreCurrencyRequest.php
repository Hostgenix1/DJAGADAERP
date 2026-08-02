<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|max:8|unique:currencies,code',
            'name' => 'required|max:100',
            'symbol' => 'nullable|max:8',
            'rate' => 'nullable|numeric',
            'is_active' => 'nullable',
        ];
    }
}
