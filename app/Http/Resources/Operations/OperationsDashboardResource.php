<?php

namespace App\Http\Resources\Operations;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationsDashboardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->resource['title'] ?? 'Operations & Planning Dashboard',
            'cards' => $this->resource['cards'] ?? [],
            'message' => $this->resource['message'] ?? 'Coming in next phase',
        ];
    }
}