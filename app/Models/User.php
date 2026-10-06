<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'phone',
        'date_of_birth',
        'gender',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getPermissionCodesAttribute()
    {
        return $this->roles
            ->flatMap(fn ($role) => $role->permissions)
            ->pluck('code')
            ->unique()
            ->values()
            ->toArray();
    }

    public function getCanAccessAdminPanelAttribute()
    {
        return $this->isAdmin()
            || $this->roles()->where('name', '!=', 'student')->exists()
            || !empty($this->getPermissionCodesAttribute());
    }

    public function hasPermission(string $code): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        return in_array($code, $this->getPermissionCodesAttribute(), true);
    }

    public function hasAnyPermission(array $codes): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        return !empty(array_intersect($codes, $this->getPermissionCodesAttribute()));
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class, 'student_id');
    }

    public function taughtCourses()
    {
        return $this->hasMany(CourseOffering::class, 'instructor_id');
    }

    public function processedPayments()
    {
        return $this->hasMany(Payment::class, 'processed_by');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isInstructor()
    {
        return $this->role === 'instructor';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isAccountant()
    {
        return $this->role === 'accountant';
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
