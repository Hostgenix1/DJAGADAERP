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
            'type' => 'required|string|in:call,email,meeting,note,whatsapp',
            'direction' => 'required|string|in:inbound,outbound',
            'communicable_type' => 'required|string',
            'communicable_id' => 'required|integer',
            'subject' => 'nullable|max:255',
            'body' => 'nullable|string',
            'contact_id' => 'nullable|integer',
            'user_id' => 'nullable|integer',
            'occurred_at' => 'required|date',
        ];
    }
}
