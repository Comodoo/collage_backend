<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseOffering extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'academic_year',
        'semester',
        'instructor_id',
        'max_students',
        'status',
    ];

    protected $casts = [
        'max_students' => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }
}
