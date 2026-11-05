<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    // Get all staff (non-students)
    public function index(Request $request)
    {
        if (!$request->user()->isAdmin() && !$request->user()->canAction('staff', 'view')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = User::with('roles')->where('role', '!=', 'student');

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        $perPage = min((int) $request->input('per_page', 100), 500);

        return response()->json($query->orderBy('created_at', 'desc')->paginate($perPage));
    }

    // Create a new staff member
    public function store(Request $request)
    {
        if (!$request->user()->isAdmin() && !$request->user()->canAction('staff', 'create')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:admin,instructor,accountant',
            'phone' => 'nullable|string|max:20',
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['string', 'exists:roles,id'],
        ];

        // If use_default_password is not true, validate custom password rules
        if (!$request->boolean('use_default_password')) {
            $rules['password'] = [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $password = $request->boolean('use_default_password') 
            ? 'P@ssw0rd2026!' 
            : $request->password;

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'password' => Hash::make($password),
        ]);

        if ($request->has('role_ids')) {
            $user->roles()->sync($request->input('role_ids', []));
        }

        return response()->json([
            'message' => 'Staff member created successfully',
            'user' => $user->load('roles')
        ], 201);
    }

    // Get a specific staff member
    public function show(Request $request, $id)
    {
        if (!$request->user()->isAdmin() && !$request->user()->canAction('staff', 'view')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::with('roles')->where('role', '!=', 'student')->findOrFail($id);
        return response()->json($user);
    }

    // Update staff member
    public function update(Request $request, $id)
    {
        if (!$request->user()->isAdmin() && !$request->user()->canAction('staff', 'update')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::with('roles')->where('role', '!=', 'student')->findOrFail($id);

        $rules = [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'role' => 'sometimes|in:admin,instructor,accountant',
            'phone' => 'nullable|string|max:20',
            'role_ids' => ['sometimes', 'array'],
            'role_ids.*' => ['string', 'exists:roles,id'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = [
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['first_name', 'last_name', 'email', 'role', 'phone']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($request->has('role_ids')) {
            $user->roles()->sync($request->input('role_ids', []));
            app(\App\Services\PermissionService::class)->forget($user);
        }

        return response()->json([
            'message' => 'Staff member updated successfully',
            'user' => $user->load('roles')
        ]);
    }

    // Delete a staff member
    public function destroy(Request $request, $id)
    {
        if (!$request->user()->isAdmin() && !$request->user()->canAction('staff', 'delete')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Prevent self-deletion
        if ($request->user()->id == $id) {
            return response()->json(['message' => 'You cannot delete your own account'], 400);
        }

        $user = User::where('role', '!=', 'student')->findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Staff member deleted successfully']);
    }
}
