<?php

namespace App\Http\Requests\Salary;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalaryStepRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('salary_scales', 'update') ?? false;
    }

    public function rules(): array
    {
        return [
            'step_number' => ['sometimes', 'integer', 'min:1'],
            'basic_salary' => ['sometimes', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}