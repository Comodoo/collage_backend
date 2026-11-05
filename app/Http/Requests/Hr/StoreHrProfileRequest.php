<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class StoreHrProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('hr', 'create') ?? false;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id', 'unique:hr_profiles,user_id'],
            'employee_number' => ['required', 'string', 'max:100', 'unique:hr_profiles,employee_number'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'position_title' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'in:permanent,contract,part_time,visiting,intern,other'],
            'date_of_employment' => ['required', 'date'],
            'contract_start_date' => ['nullable', 'date'],
            'contract_end_date' => ['nullable', 'date', 'after_or_equal:contract_start_date'],
            'employment_status' => ['sometimes', 'in:active,on_leave,suspended,resigned,terminated,retired'],
            'qualification_summary' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ];
    }
}