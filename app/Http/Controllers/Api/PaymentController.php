<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('registration');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('registration_id')) {
            $query->where('registration_id', $request->registration_id);
        }

        if ($request->user()->isStudent()) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            });
        }

        // Accountants and admins can see all payments
        if ($request->user()->isAccountant() || $request->user()->isAdmin()) {
            $query->with(['registration', 'student', 'processor']);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'fee_type' => 'required|in:registration_fee,tuition_fee,library_fee,laboratory_fee,examination_fee,hostel_fee,other',
            'method' => 'required|in:mpesa,card,bank_transfer,cash',
            'phone_number' => 'nullable|string|min:10',
        ]);

        $registration = Registration::findOrFail($validated['registration_id']);

        // Check authorization
        if ($request->user()->isStudent() && $registration->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $feeConfig = [
            'registration_fee' => ['label' => 'Registration Fee', 'amount' => 5000],
            'tuition_fee' => ['label' => 'Tuition Fee', 'amount' => 150000],
            'library_fee' => ['label' => 'Library Fee', 'amount' => 2000],
            'laboratory_fee' => ['label' => 'Laboratory Fee', 'amount' => 3000],
            'examination_fee' => ['label' => 'Examination Fee', 'amount' => 1500],
            'hostel_fee' => ['label' => 'Hostel Fee', 'amount' => 10000],
            'other' => ['label' => 'Other Fee', 'amount' => 1000],
        ];

        $fee = $feeConfig[$validated['fee_type']] ?? ['label' => 'Other Fee', 'amount' => 1000];

        // Generate unique control number
        $controlNumber = 'CN' . now()->year . str_pad(random_int(1, 999999), 6, '0', STR_PAD_LEFT);

        // Create pending payment
        $payment = Payment::create([
            'registration_id' => $validated['registration_id'],
            'student_id' => $registration->user_id,
            'fee_type' => $validated['fee_type'],
            'description' => $fee['label'],
            'amount' => $fee['amount'],
            'currency' => 'KES',
            'control_number' => $controlNumber,
            'method' => $validated['method'],
            'transaction_id' => $validated['method'] !== 'cash' ? Str::uuid() : null,
            'status' => 'pending',
        ]);

        // Simulate payment processing for non-cash methods
        if (in_array($validated['method'], ['mpesa', 'card', 'bank_transfer'])) {
            // In production, integrate with actual payment gateways
            // For now, simulate success
            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            // Update registration status if registration fee paid
            if ($validated['fee_type'] === 'registration_fee') {
                $registration->update(['status' => 'payment_completed']);
            }
        }

        return response()->json([
            'message' => 'Payment processed successfully',
            'payment' => $payment,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $payment = Payment::with('registration')->findOrFail($id);

        // Check authorization
        if ($request->user()->isStudent() && $payment->registration->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($payment);
    }

    public function verifyCashPayment(Request $request, $id)
    {
        if (!$request->user()->isAdmin() && !$request->user()->isAccountant()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $payment = Payment::findOrFail($id);

        if ($payment->method !== 'cash' && $payment->method !== 'bank_transfer') {
            return response()->json(['message' => 'Only cash or bank transfer payments can be verified manually'], 400);
        }

        $payment->update([
            'status' => 'completed',
            'paid_at' => now(),
            'processed_by' => $request->user()->id,
        ]);

        // Update registration status if registration fee paid
        if ($payment->fee_type === 'registration_fee') {
            $payment->registration->update(['status' => 'payment_completed']);
        }

        return response()->json([
            'message' => 'Cash payment verified successfully',
            'payment' => $payment,
        ]);
    }

    public function getPaymentsByRegistration(Request $request, $registrationId)
    {
        $registration = Registration::findOrFail($registrationId);

        // Check authorization
        if ($request->user()->isStudent() && $registration->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $payments = Payment::where('registration_id', $registrationId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($payments);
    }
}
