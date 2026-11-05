<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHrProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('hr', 'update') ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'user_id' => ['sometimes', 'integer', 'exists:users,id', 'unique:hr_profiles,user_id,' . $id],
            'employee_number' => ['sometimes', 'string', 'max:100', 'unique:hr_profiles,employee_number,' . $id],
            'department_id' => ['sometimes', 'integer', 'exists:departments,id'],
            'position_title' => ['sometimes', 'string', 'max:255'],
            'employment_type' => ['sometimes', 'in:permanent,contract,part_time,visiting,intern,other'],
            'date_of_employment' => ['sometimes', 'date'],
            'contract_start_date' => ['nullable', 'date'],
            'contract_end_date' => ['nullable', 'date'],
            'employment_status' => ['sometimes', 'in:active,on_leave,suspended,resigned,terminated,retired'],
            'qualification_summary' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ];
    }
}