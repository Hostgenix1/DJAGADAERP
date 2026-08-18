<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'email' => 'nullable|email|max:255|unique:customers,email',
            'phone' => 'nullable|max:50',
            'address' => 'nullable',
            'city' => 'nullable|max:100',
            'emirate' => 'nullable|max:100',
            'country' => 'nullable|max:100',
            'tax_registration_number' => 'nullable|max:20',
            'po_box' => 'nullable|max:20',
            'postal_code' => 'nullable|max:10',
            'currency_id' => 'nullable',
            'is_active' => 'nullable',
        ];
    }
}
