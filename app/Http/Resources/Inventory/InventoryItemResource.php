<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'asset_tag' => $this->asset_tag,
            'name' => $this->name,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category', fn () => new InventoryCategoryResource($this->category)),
            'location_id' => $this->location_id,
            'location' => $this->whenLoaded('location', fn () => new InventoryLocationResource($this->location)),
            'supplier_name' => $this->supplier_name,
            'purchase_date' => $this->purchase_date,
            'purchase_cost' => $this->purchase_cost,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'condition' => $this->condition,
            'status' => $this->status,
            'custodian_id' => $this->custodian_id,
            'custodian' => $this->whenLoaded('custodian', fn () => $this->custodian ? [
                'id' => $this->custodian->id,
                'name' => $this->custodian->first_name . ' ' . $this->custodian->last_name,
                'email' => $this->custodian->email,
            ] : null),
            'serial_number' => $this->serial_number,
            'notes' => $this->notes,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}