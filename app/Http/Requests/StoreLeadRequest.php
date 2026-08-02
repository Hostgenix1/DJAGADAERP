<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => 'required|max:255',
            'contact_name' => 'nullable|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:50',
            'expected_amount' => 'nullable|numeric',
            'currency_id' => 'nullable',
            'expected_date' => 'nullable',
            'owner_id' => 'nullable',
            'customer_id' => 'nullable',
            'note' => 'nullable',
        ];
    }
}
