<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrLeaveRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'hr_profile_id', 'leave_type', 'start_date', 'end_date', 'days',
        'status', 'reason', 'approved_by', 'approved_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'days' => 'integer',
    ];

    public function profile()
    {
        return $this->belongsTo(HrProfile::class, 'hr_profile_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}