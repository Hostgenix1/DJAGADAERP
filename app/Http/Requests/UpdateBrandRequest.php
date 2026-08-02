<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|max:150|unique:brands,name',
            'slug' => 'sometimes|max:150',
            'description' => 'sometimes|nullable',
        ];
    }
}
