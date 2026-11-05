<?php

namespace App\Http\Requests\Proposal;

use Illuminate\Foundation\Http\FormRequest;

class StoreProposalActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('proposals', 'update') ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'responsible_person_id' => ['nullable', 'integer', 'exists:users,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'budget_amount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['sometimes', 'in:planned,in_progress,completed,delayed,cancelled'],
            'progress_percentage' => ['sometimes', 'numeric', 'min:0', 'max:100'],
        ];
    }
}