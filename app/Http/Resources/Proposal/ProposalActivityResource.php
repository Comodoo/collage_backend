<?php

namespace App\Http\Resources\Proposal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProposalActivityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'proposal_id' => $this->proposal_id,
            'title' => $this->title,
            'description' => $this->description,
            'responsible_person_id' => $this->responsible_person_id,
            'responsible_person' => $this->whenLoaded('responsiblePerson', fn () => $this->responsiblePerson ? ['id' => $this->responsiblePerson->id, 'name' => $this->responsiblePerson->first_name . ' ' . $this->responsiblePerson->last_name] : null),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'budget_amount' => $this->budget_amount,
            'status' => $this->status,
            'progress_percentage' => $this->progress_percentage,
        ];
    }
}