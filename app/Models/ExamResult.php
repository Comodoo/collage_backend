<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_offering_id',
        'cat1_score',
        'cat2_score',
        'assignment_score',
        'final_exam_score',
        'total_score',
        'grade',
        'gpa_points',
        'status',
        'remarks',
        'recorded_by',
        'published_at',
    ];

    protected $casts = [
        'cat1_score' => 'decimal:2',
        'cat2_score' => 'decimal:2',
        'assignment_score' => 'decimal:2',
        'final_exam_score' => 'decimal:2',
        'total_score' => 'decimal:2',
        'gpa_points' => 'decimal:2',
        'published_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function courseOffering()
    {
        return $this->belongsTo(CourseOffering::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function isPublished()
    {
        return $this->status === 'published';
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
