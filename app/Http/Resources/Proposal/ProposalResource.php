<?php

namespace App\Http\Resources\Proposal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProposalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $activities = $this->whenLoaded('activities', fn () => $this->activities, collect());
        $progress = $activities->count() > 0 ? round($activities->avg('progress_percentage'), 2) : 0;

        return [
            'id' => $this->id,
            'reference_number' => $this->reference_number,
            'title' => $this->title,
            'type' => $this->type,
            'department_id' => $this->department_id,
            'department' => $this->whenLoaded('department', fn () => $this->department ? ['id' => $this->department->id, 'name' => $this->department->name, 'code' => $this->department->code] : null),
            'owner_id' => $this->owner_id,
            'owner' => $this->whenLoaded('owner', fn () => $this->owner ? ['id' => $this->owner->id, 'name' => $this->owner->first_name . ' ' . $this->owner->last_name] : null),
            'summary' => $this->summary,
            'background' => $this->background,
            'objectives' => $this->objectives,
            'expected_outcomes' => $this->expected_outcomes,
            'implementation_period_start' => $this->implementation_period_start,
            'implementation_period_end' => $this->implementation_period_end,
            'total_budget_amount' => $this->total_budget_amount,
            'funding_source' => $this->funding_source,
            'status' => $this->status,
            'submitted_by' => $this->submitted_by,
            'submitted_at' => $this->submitted_at,
            'reviewed_by' => $this->reviewed_by,
            'reviewed_at' => $this->reviewed_at,
            'approved_by' => $this->approved_by,
            'approved_at' => $this->approved_at,
            'published_at' => $this->published_at,
            'rejection_reason' => $this->rejection_reason,
            'implementation_progress' => $progress,
            'activities' => ProposalActivityResource::collection($this->whenLoaded('activities')),
            'kpis' => ProposalKpiResource::collection($this->whenLoaded('kpis')),
            'attachments' => ProposalAttachmentResource::collection($this->whenLoaded('attachments')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}