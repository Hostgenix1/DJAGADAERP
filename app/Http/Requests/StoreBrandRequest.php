<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:150|unique:brands,name',
            'slug' => 'nullable|max:150',
            'description' => 'nullable',
        ];
    }
}
