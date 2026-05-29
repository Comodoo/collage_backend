<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'registration_number',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'phone',
        'email',
        'address',
        'city',
        'country',
        'national_id',
        'national_id_type',
        'national_id_expiry_date',
        'program_id',
        'program_name',
        'department',
        'intake',
        'study_mode',
        'guardian_name',
        'guardian_phone',
        'guardian_email',
        'guardian_relationship',
        'guardian_address',
        'status',
        'rejection_reason',
        'submitted_at',
        'approved_at',
        'reviewed_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'national_id_expiry_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function academicQualifications()
    {
        return $this->hasMany(AcademicQualification::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
