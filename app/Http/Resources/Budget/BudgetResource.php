<?php

namespace App\Http\Resources\Budget;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BudgetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fiscal_year_id' => $this->fiscal_year_id,
            'fiscal_year' => $this->whenLoaded('fiscalYear', fn () => $this->fiscalYear ? ['id' => $this->fiscalYear->id, 'name' => $this->fiscalYear->name] : null),
            'department_id' => $this->department_id,
            'department' => $this->whenLoaded('department', fn () => $this->department ? ['id' => $this->department->id, 'name' => $this->department->name, 'code' => $this->department->code] : null),
            'title' => $this->title,
            'description' => $this->description,
            'total_requested_amount' => $this->total_requested_amount,
            'total_approved_amount' => $this->total_approved_amount,
            'status' => $this->status,
            'submitted_by' => $this->submitted_by,
            'submitted_at' => $this->submitted_at,
            'reviewed_by' => $this->reviewed_by,
            'reviewed_at' => $this->reviewed_at,
            'approved_by' => $this->approved_by,
            'approved_at' => $this->approved_at,
            'rejection_reason' => $this->rejection_reason,
            'lines' => BudgetLineResource::collection($this->whenLoaded('lines')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}