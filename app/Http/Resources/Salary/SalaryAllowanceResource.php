<?php

namespace App\Http\Resources\Salary;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalaryAllowanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,
            'amount' => $this->amount,
            'percentage' => $this->percentage,
            'applies_to' => $this->applies_to,
            'salary_grade_id' => $this->salary_grade_id,
            'grade' => $this->whenLoaded('grade', fn () => $this->grade ? ['id' => $this->grade->id, 'name' => $this->grade->name] : null),
            'employment_type' => $this->employment_type,
            'is_active' => (bool) $this->is_active,
        ];
    }
}