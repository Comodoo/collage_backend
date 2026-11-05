<?php

namespace App\Http\Resources\Salary;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffSalaryPlacementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hr_profile_id' => $this->hr_profile_id,
            'profile' => $this->whenLoaded('profile', fn () => $this->profile ? [
                'id' => $this->profile->id,
                'employee_number' => $this->profile->employee_number,
                'position_title' => $this->profile->position_title,
                'user' => $this->profile->user ? ['id' => $this->profile->user->id, 'name' => $this->profile->user->first_name . ' ' . $this->profile->user->last_name] : null,
                'department' => $this->profile->department ? ['id' => $this->profile->department->id, 'name' => $this->profile->department->name] : null,
                'employment_type' => $this->profile->employment_type,
            ] : null),
            'salary_grade_id' => $this->salary_grade_id,
            'grade' => $this->whenLoaded('grade', fn () => $this->grade ? ['id' => $this->grade->id, 'name' => $this->grade->name, 'code' => $this->grade->code] : null),
            'salary_step_id' => $this->salary_step_id,
            'step' => $this->whenLoaded('step', fn () => $this->step ? ['id' => $this->step->id, 'step_number' => $this->step->step_number, 'basic_salary' => $this->step->basic_salary] : null),
            'basic_salary' => $this->basic_salary,
            'effective_from' => $this->effective_from,
            'effective_to' => $this->effective_to,
            'remarks' => $this->remarks,
            'created_at' => $this->created_at,
        ];
    }
}