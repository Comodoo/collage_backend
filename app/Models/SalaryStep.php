<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStep extends Model
{
    use HasFactory;

    protected $fillable = ['salary_grade_id', 'step_number', 'basic_salary', 'is_active'];

    protected $casts = ['basic_salary' => 'decimal:2', 'is_active' => 'boolean'];

    public function grade()
    {
        return $this->belongsTo(SalaryGrade::class, 'salary_grade_id');
    }
}