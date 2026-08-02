<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommunicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'communicable_type' => 'sometimes|nullable',
            'communicable_id' => 'sometimes|nullable',
            'subject' => 'sometimes|max:255',
            'body' => 'sometimes|nullable',
            'contact_id' => 'sometimes|nullable',
            'user_id' => 'sometimes|nullable',
            'occurred_at' => 'sometimes|nullable',
        ];
    }
}
