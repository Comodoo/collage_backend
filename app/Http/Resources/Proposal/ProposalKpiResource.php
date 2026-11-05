<?php

namespace App\Http\Resources\Proposal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProposalKpiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'proposal_id' => $this->proposal_id,
            'name' => $this->name,
            'target_value' => $this->target_value,
            'actual_value' => $this->actual_value,
            'unit' => $this->unit,
            'measurement_frequency' => $this->measurement_frequency,
            'status' => $this->status,
            'remarks' => $this->remarks,
        ];
    }
}