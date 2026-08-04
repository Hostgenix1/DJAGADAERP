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
            'type' => 'required|string',
            'followable_type' => 'required',
            'followable_id' => 'required',
            'due_date' => 'required',
            'completed_at' => 'nullable',
            'note' => 'nullable',
            'assigned_to' => 'nullable',
        ];
    }
}
