<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class AssignInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('inventory', 'assign') ?? false;
    }

    public function rules(): array
    {
        return [
            'to_user_id' => ['required', 'integer', 'exists:users,id'],
            'quantity' => ['sometimes', 'integer', 'min:1'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}