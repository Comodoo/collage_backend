<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'employee_number', 'department_id', 'position_title',
        'employment_type', 'date_of_employment', 'contract_start_date',
        'contract_end_date', 'employment_status', 'qualification_summary',
        'emergency_contact_name', 'emergency_contact_phone', 'notes',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'date_of_employment' => 'date',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function documents()
    {
        return $this->hasMany(HrDocument::class, 'hr_profile_id');
    }

    public function leaveRecords()
    {
        return $this->hasMany(HrLeaveRecord::class, 'hr_profile_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}