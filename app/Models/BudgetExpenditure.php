<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetExpenditure extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_line_id', 'expenditure_date', 'description', 'amount',
        'reference_number', 'payment_method', 'status',
        'recorded_by', 'verified_by', 'verified_at',
    ];

    protected $casts = [
        'expenditure_date' => 'date',
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    public function line()
    {
        return $this->belongsTo(BudgetLine::class, 'budget_line_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}