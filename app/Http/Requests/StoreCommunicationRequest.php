<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommunicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string',
            'direction' => 'required|string|in:inbound,outbound',
            'communicable_type' => 'required',
            'communicable_id' => 'required',
            'subject' => 'nullable|max:255',
            'body' => 'nullable',
            'contact_id' => 'nullable',
            'user_id' => 'nullable',
            'occurred_at' => 'required',
        ];
    }
}
