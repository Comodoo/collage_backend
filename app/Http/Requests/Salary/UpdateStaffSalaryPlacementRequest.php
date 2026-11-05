<?php

namespace App\Http\Requests\Salary;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffSalaryPlacementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('salary_scales', 'assign') ?? false;
    }

    public function rules(): array
    {
        return [
            'hr_profile_id' => ['sometimes', 'integer', 'exists:hr_profiles,id'],
            'salary_grade_id' => ['sometimes', 'integer', 'exists:salary_grades,id'],
            'salary_step_id' => ['sometimes', 'integer', 'exists:salary_steps,id'],
            'basic_salary' => ['sometimes', 'numeric', 'min:0'],
            'effective_from' => ['sometimes', 'date'],
            'effective_to' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}