<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats(Request $request)
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $totalStudents = User::where('role', 'student')->count();
        $totalStaff = User::whereIn('role', ['admin', 'instructor', 'accountant'])->count();
        $pendingRegistrations = Registration::where('status', 'pending')->count();
        
        // Sum total revenue from completed payments
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        
        // Format revenue (e.g. 1635000 -> 1,635,000)
        $formattedRevenue = number_format($totalRevenue, 0) . ' TSH';

        return response()->json([
            'totalStudents' => $totalStudents,
            'totalStaff' => $totalStaff,
            'pendingRegistrations' => $pendingRegistrations,
            'totalRevenue' => $formattedRevenue,
            // We can return 0 for these since results aren't fully implemented in DB yet
            'passedStudents' => 0,
            'failedStudents' => 0,
        ]);
    }
}
