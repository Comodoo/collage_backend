<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class TransferInventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('inventory', 'transfer') ?? false;
    }

    public function rules(): array
    {
        return [
            'to_location_id' => ['required', 'integer', 'exists:inventory_locations,id'],
            'quantity' => ['sometimes', 'integer', 'min:1'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}