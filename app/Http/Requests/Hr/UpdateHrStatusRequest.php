<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHrStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('hr', 'status.manage') ?? false;
    }

    public function rules(): array
    {
        return [
            'employment_status' => ['required', 'in:active,on_leave,suspended,resigned,terminated,retired'],
            'notes' => ['nullable', 'string'],
        ];
    }
}