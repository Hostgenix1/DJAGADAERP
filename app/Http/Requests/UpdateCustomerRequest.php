<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customerId = $this->route('customer')?->id;

        return [
            'company_name' => 'sometimes|max:255',
            'contact_person' => 'sometimes|max:255',
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('customers', 'email')->ignore($customerId)],
            'phone' => 'sometimes|max:50',
            'address' => 'sometimes|nullable',
            'city' => 'sometimes|max:100',
            'emirate' => 'sometimes|nullable|max:100',
            'country' => 'sometimes|max:100',
            'tax_registration_number' => 'sometimes|nullable|max:20',
            'po_box' => 'sometimes|nullable|max:20',
            'postal_code' => 'sometimes|nullable|max:10',
            'currency_id' => 'sometimes|nullable',
            'is_active' => 'sometimes|nullable',
        ];
    }
}
