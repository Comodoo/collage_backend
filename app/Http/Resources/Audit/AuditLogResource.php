<?php

namespace App\Http\Resources\Audit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user' => $this->user ? ['id' => $this->user->id, 'name' => $this->user->first_name . ' ' . $this->user->last_name, 'email' => $this->user->email] : null,
            'method' => $this->method,
            'path' => $this->path,
            'status_code' => $this->status_code,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'meta' => $this->meta,
            'created_at' => $this->created_at,
        ];
    }
}