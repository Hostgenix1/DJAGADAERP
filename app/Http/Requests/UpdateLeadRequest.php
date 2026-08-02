<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => 'sometimes|max:255',
            'contact_name' => 'sometimes|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|max:50',
            'expected_amount' => 'sometimes|numeric',
            'currency_id' => 'sometimes|nullable',
            'expected_date' => 'sometimes|nullable',
            'owner_id' => 'sometimes|nullable',
            'customer_id' => 'sometimes|nullable',
            'note' => 'sometimes|nullable',
        ];
    }
}
