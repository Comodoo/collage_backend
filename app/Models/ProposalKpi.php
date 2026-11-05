<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalKpi extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id', 'name', 'target_value', 'actual_value', 'unit',
        'measurement_frequency', 'status', 'remarks',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }
}