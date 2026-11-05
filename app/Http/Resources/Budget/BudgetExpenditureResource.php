<?php

namespace App\Http\Resources\Budget;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BudgetExpenditureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'budget_line_id' => $this->budget_line_id,
            'expenditure_date' => $this->expenditure_date,
            'description' => $this->description,
            'amount' => $this->amount,
            'reference_number' => $this->reference_number,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'recorded_by' => $this->recorded_by,
            'verified_by' => $this->verified_by,
            'verified_at' => $this->verified_at,
            'created_at' => $this->created_at,
        ];
    }
}