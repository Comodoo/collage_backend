<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'credit_hours',
        'credit_id',
        'department_id',
        'program_id',
        'status',
        'created_by',
    ];

    protected $casts = [
        'credit_hours' => 'integer',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }

    public function offerings()
    {
        return $this->hasMany(CourseOffering::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function instructorAssignments()
    {
        return $this->hasMany(InstructorAssignment::class);
    }
}
