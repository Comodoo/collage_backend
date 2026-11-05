<?php

namespace App\Http\Requests\Budget;

use Illuminate\Foundation\Http\FormRequest;

class StoreBudgetCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('budgets', 'create') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:100', 'unique:budget_categories,code'],
            'description' => ['nullable', 'string'],
        ];
    }
}