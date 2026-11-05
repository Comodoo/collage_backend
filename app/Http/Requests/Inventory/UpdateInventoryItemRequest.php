<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('inventory', 'update') ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'asset_tag' => ['sometimes', 'string', 'max:100', 'unique:inventory_items,asset_tag,' . $id],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['sometimes', 'integer', 'exists:inventory_categories,id'],
            'location_id' => ['sometimes', 'integer', 'exists:inventory_locations,id'],
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_cost' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['sometimes', 'integer', 'min:0'],
            'unit' => ['nullable', 'string', 'max:50'],
            'condition' => ['sometimes', 'in:new,good,fair,poor,damaged'],
            'status' => ['sometimes', 'in:available,assigned,under_repair,lost,disposed'],
            'custodian_id' => ['nullable', 'integer', 'exists:users,id'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}