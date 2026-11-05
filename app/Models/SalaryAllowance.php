<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryAllowance extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'type', 'amount', 'percentage',
        'applies_to', 'salary_grade_id', 'employment_type', 'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function grade()
    {
        return $this->belongsTo(SalaryGrade::class, 'salary_grade_id');
    }
}