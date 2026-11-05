<?php

namespace App\Http\Resources\Salary;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalaryStepResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'salary_grade_id' => $this->salary_grade_id,
            'step_number' => $this->step_number,
            'basic_salary' => $this->basic_salary,
            'is_active' => (bool) $this->is_active,
        ];
    }
}