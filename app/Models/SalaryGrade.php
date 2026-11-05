<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryGrade extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function steps()
    {
        return $this->hasMany(SalaryStep::class, 'salary_grade_id');
    }

    public function placements()
    {
        return $this->hasMany(StaffSalaryPlacement::class, 'salary_grade_id');
    }
}