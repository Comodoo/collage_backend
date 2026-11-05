<?php

namespace App\Http\Resources\Budget;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BudgetLineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $approved = (float) $this->approved_amount;
        $spent = (float) $this->spent_amount;

        return [
            'id' => $this->id,
            'budget_id' => $this->budget_id,
            'budget_category_id' => $this->budget_category_id,
            'category' => $this->whenLoaded('category', fn () => $this->category ? ['id' => $this->category->id, 'name' => $this->category->name, 'code' => $this->category->code] : null),
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'unit_cost' => $this->unit_cost,
            'requested_amount' => $this->requested_amount,
            'approved_amount' => $this->approved_amount,
            'spent_amount' => $this->spent_amount,
            'variance' => $approved - $spent,
            'utilization_percentage' => $approved > 0 ? round(($spent / $approved) * 100, 2) : 0,
            'status' => $this->status,
        ];
    }
}