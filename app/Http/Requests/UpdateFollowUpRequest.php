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
            'followable_type' => 'sometimes|nullable',
            'followable_id' => 'sometimes|nullable',
            'due_date' => 'sometimes|nullable',
            'completed_at' => 'sometimes|nullable',
            'note' => 'sometimes|nullable',
            'assigned_to' => 'sometimes|nullable',
        ];
    }
}
