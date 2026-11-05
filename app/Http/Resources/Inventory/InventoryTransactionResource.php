<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'inventory_item_id' => $this->inventory_item_id,
            'transaction_type' => $this->transaction_type,
            'from_location_id' => $this->from_location_id,
            'from_location' => $this->whenLoaded('fromLocation', fn () => $this->fromLocation ? new InventoryLocationResource($this->fromLocation) : null),
            'to_location_id' => $this->to_location_id,
            'to_location' => $this->whenLoaded('toLocation', fn () => $this->toLocation ? new InventoryLocationResource($this->toLocation) : null),
            'from_user_id' => $this->from_user_id,
            'from_user' => $this->whenLoaded('fromUser', fn () => $this->fromUser ? ['id' => $this->fromUser->id, 'name' => $this->fromUser->first_name . ' ' . $this->fromUser->last_name] : null),
            'to_user_id' => $this->to_user_id,
            'to_user' => $this->whenLoaded('toUser', fn () => $this->toUser ? ['id' => $this->toUser->id, 'name' => $this->toUser->first_name . ' ' . $this->toUser->last_name] : null),
            'quantity' => $this->quantity,
            'condition' => $this->condition,
            'remarks' => $this->remarks,
            'transaction_date' => $this->transaction_date,
            'recorded_by' => $this->recorded_by,
            'recorder' => $this->whenLoaded('recorder', fn () => $this->recorder ? ['id' => $this->recorder->id, 'name' => $this->recorder->first_name . ' ' . $this->recorder->last_name] : null),
            'created_at' => $this->created_at,
        ];
    }
}