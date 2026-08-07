<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFollowUpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'sometimes|string|in:call,email,meeting,note,task',
            'followable_type' => 'sometimes|nullable',
            'followable_id' => 'sometimes|nullable|integer',
            'due_date' => 'sometimes|nullable|date',
            'completed_at' => 'nullable|date',
            'note' => 'sometimes|nullable|string',
            'assigned_to' => 'sometimes|nullable|string|max:255',
        ];
    }
}
