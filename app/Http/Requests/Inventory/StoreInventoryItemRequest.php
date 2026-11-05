<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('inventory', 'create') ?? false;
    }

    public function rules(): array
    {
        return [
            'asset_tag' => ['required', 'string', 'max:100', 'unique:inventory_items,asset_tag'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'integer', 'exists:inventory_categories,id'],
            'location_id' => ['required', 'integer', 'exists:inventory_locations,id'],
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_cost' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit' => ['nullable', 'string', 'max:50'],
            'condition' => ['required', 'in:new,good,fair,poor,damaged'],
            'status' => ['sometimes', 'in:available,assigned,under_repair,lost,disposed'],
            'custodian_id' => ['nullable', 'integer', 'exists:users,id'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}