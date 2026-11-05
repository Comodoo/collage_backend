<?php

namespace App\Http\Requests\Budget;

use Illuminate\Foundation\Http\FormRequest;

class StoreBudgetExpenditureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('budgets', 'update') ?? false;
    }

    public function rules(): array
    {
        return [
            'expenditure_date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'payment_method' => ['nullable', 'string', 'max:50'],
        ];
    }
}