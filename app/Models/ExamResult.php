<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    public const WEIGHT_CAT1 = 0.10;

    public const WEIGHT_CAT2 = 0.10;

    public const WEIGHT_ASSIGNMENT = 0.10;

    public const WEIGHT_FINAL = 0.70;

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
        'submitted_by',
        'submitted_at',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'published_at',
    ];

    protected $casts = [
        'cat1_score' => 'decimal:2',
        'cat2_score' => 'decimal:2',
        'assignment_score' => 'decimal:2',
        'final_exam_score' => 'decimal:2',
        'total_score' => 'decimal:2',
        'gpa_points' => 'decimal:2',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public const STATUS_DRAFT = 'draft';

    public const STATUS_SUBMITTED = 'submitted';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_REJECTED = 'rejected';

    protected static function booted(): void
    {
        static::saving(function (ExamResult $result) {
            $hasScores = $result->cat1_score !== null || $result->cat2_score !== null
                || $result->assignment_score !== null || $result->final_exam_score !== null;

            if ($hasScores) {
                $cat1 = (float) ($result->cat1_score ?? 0);
                $cat2 = (float) ($result->cat2_score ?? 0);
                $assignment = (float) ($result->assignment_score ?? 0);
                $final = (float) ($result->final_exam_score ?? 0);

                $result->total_score = round(
                    ($cat1 * self::WEIGHT_CAT1) + ($cat2 * self::WEIGHT_CAT2)
                    + ($assignment * self::WEIGHT_ASSIGNMENT) + ($final * self::WEIGHT_FINAL),
                    2
                );

                $grade = self::calculateGrade($result->total_score);
                $result->grade = $grade['grade'];
                $result->gpa_points = $grade['gpa'];
            }
        });
    }

    /**
     * @return array{grade: string, gpa: float}
     */
    public static function calculateGrade(float $score): array
    {
        if ($score >= 70) return ['grade' => 'A', 'gpa' => 5.0];
        if ($score >= 60) return ['grade' => 'B+', 'gpa' => 4.0];
        if ($score >= 50) return ['grade' => 'B', 'gpa' => 3.0];
        if ($score >= 40) return ['grade' => 'C', 'gpa' => 2.0];
        if ($score >= 35) return ['grade' => 'D', 'gpa' => 1.0];
        return ['grade' => 'E', 'gpa' => 0.0];
    }

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

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isDraft()
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isSubmitted()
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    public function isPublished()
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function canBeEdited(): bool
    {
        return $this->isDraft() || $this->isRejected();
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', self::STATUS_SUBMITTED);
    }
}
