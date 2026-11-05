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

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')
            ->withTimestamps();
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
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

    public function isOperations()
    {
        return $this->role === 'operations';
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Permission codes granted through this user's dynamic roles.
     * Users with the built-in admin role always receive a wildcard.
     */
    public function permissionCodes(): array
    {
        if ($this->isAdmin()) {
            return ['*'];
        }

        return Permission::whereIn('id', function ($query) {
            $query->select('permission_id')
                ->from('role_permissions')
                ->whereIn('role_id', $this->roles()->pluck('roles.id'));
        })->pluck('code')->all();
    }

    public function canAction(string $module, string $action): bool
    {
        $codes = $this->permissionCodes();

        if (in_array('*', $codes, true)) {
            return true;
        }

        return in_array($module . '.' . $action, $codes, true);
    }

    public function canAnyAction(string $module, array $actions): bool
    {
        foreach ($actions as $action) {
            if ($this->canAction($module, $action)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Whether the user may access the admin panel at all: either the built-in
     * admin role or any dynamic role carrying an admin-panel permission.
     */
    public function canAccessAdminPanel(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->roles()->exists()) {
            $codes = $this->permissionCodes();
            if ($codes !== ['*'] && count($codes) > 0) {
                return true;
            }
        }

        return false;
    }
}
