<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'department_id',
        'duration',
        'description',
        'requirements',
        'tuition_fee',
        'fees',
        'currency',
        'is_active',
    ];

    protected $casts = [
        'duration' => 'integer',
        'tuition_fee' => 'decimal:2',
        'requirements' => 'array',
        'fees' => 'array',
        'is_active' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
