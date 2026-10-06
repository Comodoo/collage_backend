<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function students(Request $request)
    {
        if (!$request->user()->hasPermission('students.view')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $students = User::with('registrations')
            ->where('role', 'student')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($students->map(function (User $user) {
            $reg = $user->registrations->sortByDesc('created_at')->first();

            return [
                'id' => (string) $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'registration_id' => $reg ? (string) $reg->id : null,
                'registration_number' => $reg->registration_number ?? null,
                'program_id' => $reg ? $reg->program_id : null,
                'program_name' => $reg->program_name ?? null,
                'department' => $reg->department ?? null,
                'intake' => $reg->intake ?? null,
                'study_mode' => $reg->study_mode ?? null,
                'registration_status' => $reg->status ?? null,
                'status' => $reg ? $reg->status : 'registered',
                'year' => 1,
                'created_at' => $user->created_at?->toISOString(),
            ];
        })->values());
    }

    // Reset a user's password (requires permission)
    public function resetPassword(Request $request, $userId)
    {
        if (!$request->user()->hasPermission('users.reset-password')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'password' => 'nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $password = $request->password ?? strtolower($user->last_name);

        $user->password = $password;
        $user->save();

        return response()->json([
            'message' => 'Password reset successfully',
            'password' => $request->filled('password') ? null : $password,
        ]);
    }
}