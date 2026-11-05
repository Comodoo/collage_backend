<?php

namespace App\Http\Requests\Proposal;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('proposals', 'update') ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', 'in:business_plan,strategic_plan,project_proposal,departmental_plan,other'],
            'department_id' => ['sometimes', 'integer', 'exists:departments,id'],
            'summary' => ['nullable', 'string'],
            'background' => ['nullable', 'string'],
            'objectives' => ['nullable', 'string'],
            'expected_outcomes' => ['nullable', 'string'],
            'implementation_period_start' => ['nullable', 'date'],
            'implementation_period_end' => ['nullable', 'date'],
            'total_budget_amount' => ['nullable', 'numeric', 'min:0'],
            'funding_source' => ['nullable', 'string', 'max:255'],
        ];
    }
}