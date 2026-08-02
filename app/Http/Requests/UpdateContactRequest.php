<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'sometimes|nullable',
            'full_name' => 'sometimes|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|max:50',
            'position' => 'sometimes|max:100',
            'is_primary' => 'sometimes|nullable',
        ];
    }
}
