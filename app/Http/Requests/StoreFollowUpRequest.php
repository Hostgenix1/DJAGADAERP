<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFollowUpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string|in:call,email,meeting,note,task',
            'followable_type' => 'required',
            'followable_id' => 'required|integer',
            'due_date' => 'required|date',
            'completed_at' => 'nullable|date',
            'note' => 'nullable|string',
            'assigned_to' => 'nullable|string|max:255',
        ];
    }
}
