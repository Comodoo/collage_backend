<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    public function __construct(protected PermissionService $permissions)
    {
    }

    public function index(Request $request)
    {
        $this->authorizeRequest($request, 'roles', 'view');

        $roles = Role::with('permissions')
            ->withCount('users')
            ->orderBy('name')
            ->get();

        return response()->json($roles);
    }

    public function permissions(Request $request)
    {
        $this->authorizeRequest($request, 'roles', 'view');

        return response()->json(Permission::orderBy('module')->orderBy('action')->get());
    }

    public function store(Request $request)
    {
        $this->authorizeRequest($request, 'roles', 'create');

        $request->validate([
            'id' => ['nullable', 'string', 'max:60', Rule::unique('roles', 'id')],
            'name' => ['required', 'string', 'max:120', Rule::unique('roles', 'name')],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'code')],
        ]);

        $id = $request->input('id') ?: 'ROLE_' . strtoupper(Str::slug($request->input('name'), '_'));

        $role = Role::create([
            'id' => $id,
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'is_system' => false,
        ]);

        $this->syncPermissions($role, $request->input('permissions', []));

        return response()->json([
            'message' => 'Role created successfully',
            'data' => $role->load('permissions'),
        ], 201);
    }

    public function update(Request $request, string $roleId)
    {
        $this->authorizeRequest($request, 'roles', 'update');

        $role = Role::findOrFail($roleId);

        $request->validate([
            'name' => ['sometimes', 'string', 'max:120', Rule::unique('roles', 'name')->ignore($roleId)],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'code')],
        ]);

        // System roles keep their identity but their permission matrix can be tuned.
        if ($role->is_system) {
            if ($request->filled('name') && $request->input('name') !== $role->name) {
                throw ValidationException::withMessages([
                    'name' => 'System roles cannot be renamed.',
                ]);
            }
            if ($request->has('description')) {
                $role->update(['description' => $request->input('description')]);
            }
        } else {
            $role->update([
                'name' => $request->input('name', $role->name),
                'description' => $request->input('description'),
            ]);
        }

        if ($request->has('permissions')) {
            $this->syncPermissions($role, $request->input('permissions'));
        }

        return response()->json([
            'message' => 'Role updated successfully',
            'data' => $role->load('permissions'),
        ]);
    }

    public function destroy(Request $request, string $roleId)
    {
        $this->authorizeRequest($request, 'roles', 'delete');

        $role = Role::findOrFail($roleId);

        if ($role->is_system) {
            throw ValidationException::withMessages([
                'id' => 'System roles cannot be deleted.',
            ]);
        }

        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }

    /**
     * List users with their assigned dynamic roles (for the admin roles UI).
     */
    public function users(Request $request)
    {
        $this->authorizeRequest($request, 'roles', 'view');

        $users = User::with('roles')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return response()->json($users);
    }

    /**
     * Sync the dynamic roles assigned to a user.
     */
    public function assign(Request $request, int $userId)
    {
        $this->authorizeRequest($request, 'roles', 'assign');

        $user = User::findOrFail($userId);

        $request->validate([
            'role_ids' => ['sometimes', 'array'],
            'role_ids.*' => ['string', Rule::exists('roles', 'id')],
        ]);

        $user->roles()->sync($request->input('role_ids', []));
        $this->permissions->forget($user);

        return response()->json([
            'message' => 'Roles updated successfully',
            'data' => $user->load('roles'),
        ]);
    }

    private function syncPermissions(Role $role, array $codes): void
    {
        $role->permissions()->sync(
            Permission::whereIn('code', $codes)->pluck('id')
        );

        // Invalidate the cached permission set for every user holding this role.
        $userIds = $role->users()->pluck('users.id');
        foreach ($userIds as $userId) {
            Cache::forget('perms:' . $userId);
        }
    }

    private function authorizeRequest(Request $request, string $module, string $action): void
    {
        if (!$request->user()->canAction($module, $action)) {
            abort(403, 'Unauthorized');
        }
    }
}