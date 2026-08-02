<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => 'sometimes|max:255',
            'contact_person' => 'sometimes|max:255',
            'email' => 'sometimes|email|max:255|unique:customers,email',
            'phone' => 'sometimes|max:50',
            'address' => 'sometimes|nullable',
            'city' => 'sometimes|max:100',
            'country' => 'sometimes|max:100',
            'currency_id' => 'sometimes|nullable',
            'is_active' => 'sometimes|nullable',
        ];
    }
}
