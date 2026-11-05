<?php

namespace App\Http\Requests\Salary;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffSalaryPlacementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('salary_scales', 'assign') ?? false;
    }

    public function rules(): array
    {
        return [
            'hr_profile_id' => ['required', 'integer', 'exists:hr_profiles,id'],
            'salary_grade_id' => ['required', 'integer', 'exists:salary_grades,id'],
            'salary_step_id' => ['required', 'integer', 'exists:salary_steps,id'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'effective_from' => ['required', 'date'],
            'effective_to' => ['nullable', 'date', 'after_or_equal:effective_from'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}