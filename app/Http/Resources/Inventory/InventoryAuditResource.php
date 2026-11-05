<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryAuditResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'audit_date' => $this->audit_date,
            'location_id' => $this->location_id,
            'location' => $this->whenLoaded('location', fn () => $this->location ? new InventoryLocationResource($this->location) : null),
            'inventory_item_id' => $this->inventory_item_id,
            'item' => $this->whenLoaded('item', fn () => $this->item ? new InventoryItemResource($this->item) : null),
            'expected_quantity' => $this->expected_quantity,
            'counted_quantity' => $this->counted_quantity,
            'variance' => $this->variance,
            'condition' => $this->condition,
            'remarks' => $this->remarks,
            'conducted_by' => $this->conducted_by,
            'conductor' => $this->whenLoaded('conductor', fn () => $this->conductor ? ['id' => $this->conductor->id, 'name' => $this->conductor->first_name . ' ' . $this->conductor->last_name] : null),
            'created_at' => $this->created_at,
        ];
    }
}