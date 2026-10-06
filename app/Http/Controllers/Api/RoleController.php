<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()->hasPermission('roles.view')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $roles = Role::with(['permissions', 'users'])->orderBy('created_at')->get();

        return response()->json($roles->map(function (Role $role) {
            return [
                'id' => (string) $role->id,
                'name' => $role->name,
                'description' => $role->description,
                'is_system' => (bool) $role->is_system,
                'users_count' => $role->users->count(),
                'permissions' => $role->permissions->map(function (Permission $p) {
                    return [
                        'id' => $p->id,
                        'code' => $p->code,
                        'module' => $p->module,
                        'action' => $p->action,
                        'label' => $p->label,
                        'hint' => $p->hint,
                    ];
                })->values(),
            ];
        })->values());
    }

    public function permissions(Request $request)
    {
        if (!$request->user()->hasPermission('roles.view')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(Permission::orderBy('module')->orderBy('action')->get());
    }

    public function users(Request $request)
    {
        if (!$request->user()->hasPermission('roles.view')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(
            User::with('roles:id,name')
                ->orderBy('created_at', 'desc')
                ->get(['id', 'first_name', 'last_name', 'email', 'role'])
                ->map(function (User $user) {
                    return [
                        'id' => $user->id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'roles' => $user->roles->map(fn ($role) => [
                            'id' => (string) $role->id,
                            'name' => $role->name,
                        ])->values(),
                    ];
                })
                ->values()
        );
    }

    public function store(Request $request)
    {
        if (!$request->user()->hasPermission('roles.create')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_system' => false,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync(
                Permission::whereIn('code', $request->permissions)->pluck('id')
            );
        }

        $role->load('permissions', 'users');

        return response()->json($this->serialize($role), 201);
    }

    public function update(Request $request, $id)
    {
        if (!$request->user()->hasPermission('roles.update')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($role->is_system) {
            $role->update(['description' => $request->description]);
        } else {
            $role->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);
        }

        if ($request->has('permissions')) {
            $role->permissions()->sync(
                Permission::whereIn('code', $request->permissions)->pluck('id')
            );
        }

        $role->load('permissions', 'users');

        return response()->json($this->serialize($role));
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()->hasPermission('roles.delete')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $role = Role::findOrFail($id);

        if ($role->is_system) {
            return response()->json(['message' => 'System roles cannot be deleted'], 422);
        }

        $role->users()->detach();
        $role->permissions()->detach();
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }

    public function assignRoles(Request $request, $userId)
    {
        if (!$request->user()->hasPermission('roles.assign')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'integer|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->roles()->sync($request->role_ids ?? []);

        return response()->json([
            'message' => 'Roles updated successfully',
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'role' => $user->role,
                'roles' => $user->roles->map(fn ($role) => [
                    'id' => (string) $role->id,
                    'name' => $role->name,
                ])->values(),
            ],
        ]);
    }

    private function serialize(Role $role): array
    {
        return [
            'id' => (string) $role->id,
            'name' => $role->name,
            'description' => $role->description,
            'is_system' => (bool) $role->is_system,
            'users_count' => $role->users->count(),
            'permissions' => $role->permissions->map(function (Permission $p) {
                return [
                    'id' => $p->id,
                    'code' => $p->code,
                    'module' => $p->module,
                    'action' => $p->action,
                    'label' => $p->label,
                    'hint' => $p->hint,
                ];
            })->values(),
        ];
    }
}