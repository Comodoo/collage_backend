<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class PermissionService
{
    /**
     * Permission codes granted via the user's active dynamic roles.
     * Built-in admin users receive a wildcard.
     *
     * @return array<int, string>
     */
    public function codesFor(User $user): array
    {
        if ($user->isAdmin()) {
            return ['*'];
        }

        return Cache::remember('perms:' . $user->id, 60, function () use ($user) {
            $roleIds = $user->roles()->pluck('roles.id');

            if ($roleIds->isEmpty()) {
                return [];
            }

            return Permission::whereIn('id', function ($query) use ($roleIds) {
                $query->select('permission_id')
                    ->from('role_permissions')
                    ->whereIn('role_id', $roleIds);
            })->pluck('code')->all();
        });
    }

    public function can(User $user, string $module, string $action): bool
    {
        $codes = $this->codesFor($user);

        if (in_array('*', $codes, true)) {
            return true;
        }

        return in_array($module . '.' . $action, $codes, true);
    }

    public function canAny(User $user, string $module, array $actions): bool
    {
        foreach ($actions as $action) {
            if ($this->can($user, $module, $action)) {
                return true;
            }
        }

        return false;
    }

    public function rolesFor(User $user): array
    {
        return $user->roles()
            ->get()
            ->map(fn (Role $role) => [
                'role_id' => $role->id,
                'name' => $role->name,
                'description' => $role->description,
                'is_system' => $role->is_system,
            ])
            ->all();
    }

    public function canAccessAdminPanel(User $user): bool
    {
        return count($this->codesFor($user)) > 0;
    }

    public function forget(User $user): void
    {
        Cache::forget('perms:' . $user->id);
    }
}