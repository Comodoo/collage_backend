<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryAuditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('inventory', 'audit') ?? false;
    }

    public function rules(): array
    {
        return [
            'audit_date' => ['required', 'date'],
            'location_id' => ['required', 'integer', 'exists:inventory_locations,id'],
            'inventory_item_id' => ['required', 'integer', 'exists:inventory_items,id'],
            'expected_quantity' => ['required', 'integer', 'min:0'],
            'counted_quantity' => ['required', 'integer', 'min:0'],
            'condition' => ['nullable', 'in:new,good,fair,poor,damaged'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}