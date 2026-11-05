<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffSalaryPlacement extends Model
{
    use HasFactory;

    protected $fillable = [
        'hr_profile_id', 'salary_grade_id', 'salary_step_id', 'basic_salary',
        'effective_from', 'effective_to', 'remarks', 'created_by',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    public function profile()
    {
        return $this->belongsTo(HrProfile::class, 'hr_profile_id');
    }

    public function grade()
    {
        return $this->belongsTo(SalaryGrade::class, 'salary_grade_id');
    }

    public function step()
    {
        return $this->belongsTo(SalaryStep::class, 'salary_step_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}