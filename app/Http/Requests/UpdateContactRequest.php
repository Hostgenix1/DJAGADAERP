<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $contactId = $this->route('contact')?->id;

        return [
            'customer_id' => 'sometimes|exists:customers,id',
            'full_name' => 'sometimes|max:255',
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('contacts', 'email')->ignore($contactId)],
            'phone' => 'sometimes|max:50',
            'position' => 'sometimes|max:100',
            'is_primary' => 'sometimes|nullable',
        ];
    }
}
