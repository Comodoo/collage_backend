<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proposal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference_number', 'title', 'type', 'department_id', 'owner_id',
        'summary', 'background', 'objectives', 'expected_outcomes',
        'implementation_period_start', 'implementation_period_end',
        'total_budget_amount', 'funding_source', 'status',
        'submitted_by', 'submitted_at', 'reviewed_by', 'reviewed_at',
        'approved_by', 'approved_at', 'published_at', 'rejection_reason',
    ];

    protected $casts = [
        'total_budget_amount' => 'decimal:2',
        'implementation_period_start' => 'date',
        'implementation_period_end' => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function activities()
    {
        return $this->hasMany(ProposalActivity::class, 'proposal_id');
    }

    public function kpis()
    {
        return $this->hasMany(ProposalKpi::class, 'proposal_id');
    }

    public function attachments()
    {
        return $this->hasMany(ProposalAttachment::class, 'proposal_id');
    }
}