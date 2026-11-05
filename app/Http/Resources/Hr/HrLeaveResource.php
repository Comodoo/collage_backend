<?php

namespace App\Http\Resources\Hr;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HrLeaveResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hr_profile_id' => $this->hr_profile_id,
            'leave_type' => $this->leave_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'days' => $this->days,
            'status' => $this->status,
            'reason' => $this->reason,
            'approved_by' => $this->approved_by,
            'approver' => $this->whenLoaded('approver', fn () => $this->approver ? [
                'id' => $this->approver->id,
                'name' => $this->approver->first_name . ' ' . $this->approver->last_name,
            ] : null),
            'approved_at' => $this->approved_at,
            'created_at' => $this->created_at,
        ];
    }
}