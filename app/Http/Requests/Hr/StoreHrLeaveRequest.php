<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class StoreHrLeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('hr', 'leave.manage') ?? false;
    }

    public function rules(): array
    {
        return [
            'leave_type' => ['required', 'in:annual,sick,maternity,paternity,study,unpaid,other'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'days' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string'],
        ];
    }
}