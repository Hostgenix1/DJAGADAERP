<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|max:50',
            'address' => 'sometimes|nullable',
            'city' => 'sometimes|max:100',
            'country' => 'sometimes|max:100',
            'tax_registration_number' => 'sometimes|max:20',
            'payment_terms' => 'sometimes|max:100',
            'currency_id' => 'nullable|exists:currencies,id',
            'default_payment_term' => 'sometimes|max:255',
            'is_active' => 'sometimes|nullable',
        ];
    }
}
