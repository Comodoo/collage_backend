<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id', 'title', 'description', 'responsible_person_id',
        'start_date', 'end_date', 'budget_amount', 'status', 'progress_percentage',
    ];

    protected $casts = [
        'budget_amount' => 'decimal:2',
        'progress_percentage' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }

    public function responsiblePerson()
    {
        return $this->belongsTo(User::class, 'responsible_person_id');
    }
}