<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => 'required|max:255',
            'contact_person' => 'nullable|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:50',
            'address' => 'nullable',
            'city' => 'nullable|max:100',
            'country' => 'nullable|max:100',
            'tax_registration_number' => 'nullable|max:20',
            'payment_terms' => 'nullable|max:100',
            'currency_id' => 'nullable|exists:currencies,id',
            'default_payment_term' => 'nullable|max:255',
            'is_active' => 'nullable',
        ];
    }
}
