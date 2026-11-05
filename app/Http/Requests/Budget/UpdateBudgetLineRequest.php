<?php

namespace App\Http\Requests\Budget;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBudgetLineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('budgets', 'update') ?? false;
    }

    public function rules(): array
    {
        return [
            'budget_category_id' => ['sometimes', 'integer', 'exists:budget_categories,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'quantity' => ['sometimes', 'numeric', 'min:0'],
            'unit_cost' => ['sometimes', 'numeric', 'min:0'],
            'requested_amount' => ['sometimes', 'numeric', 'min:0'],
            'approved_amount' => ['sometimes', 'numeric', 'min:0'],
            'status' => ['sometimes', 'in:pending,approved,rejected,closed'],
        ];
    }
}