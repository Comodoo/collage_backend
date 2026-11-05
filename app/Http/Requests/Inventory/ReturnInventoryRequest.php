<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class ReturnInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('inventory', 'return') ?? false;
    }

    public function rules(): array
    {
        return [
            'quantity' => ['sometimes', 'integer', 'min:1'],
            'location_id' => ['nullable', 'integer', 'exists:inventory_locations,id'],
            'condition' => ['nullable', 'in:new,good,fair,poor,damaged'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}