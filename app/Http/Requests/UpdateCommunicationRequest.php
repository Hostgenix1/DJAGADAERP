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
            'type' => 'sometimes|string|in:call,email,meeting,note,whatsapp',
            'direction' => 'sometimes|string|in:inbound,outbound',
            'communicable_type' => 'sometimes|nullable|string',
            'communicable_id' => 'sometimes|nullable|integer',
            'subject' => 'sometimes|max:255',
            'body' => 'sometimes|nullable|string',
            'contact_id' => 'sometimes|nullable|integer',
            'user_id' => 'sometimes|nullable|integer',
            'occurred_at' => 'sometimes|nullable|date',
        ];
    }
}
