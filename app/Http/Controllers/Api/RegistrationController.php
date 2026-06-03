<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicQualification;
use App\Models\Document;
use App\Models\Payment;
use App\Models\Program;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $query = Registration::with(['academicQualifications', 'payments', 'documents']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->user()->isStudent()) {
            $query->where('user_id', $request->user()->id);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'national_id' => 'required|string|min:8',
            'national_id_type' => 'nullable|in:passport,national_id,birth_certificate',
            'national_id_expiry_date' => 'nullable|date',
            'phone' => 'required|string|min:10',
            'email' => 'required|email',
            'address' => 'required|string|min:5',
            'city' => 'required|string|min:2',
            'country' => 'required|string|min:2',
            'academic_qualifications' => 'required|array|min:1',
            'academic_qualifications.*.level' => 'required|in:o_level,a_level,certificate,diploma,degree,other',
            'academic_qualifications.*.institution_name' => 'required|string|min:2',
            'academic_qualifications.*.institution_address' => 'required|string|min:5',
            'academic_qualifications.*.country' => 'required|string|min:2',
            'academic_qualifications.*.start_date' => 'required|date',
            'academic_qualifications.*.end_date' => 'required|date',
            'academic_qualifications.*.examination_board' => 'nullable|string',
            'academic_qualifications.*.index_number' => 'nullable|string',
            'academic_qualifications.*.grade' => 'nullable|string',
            'academic_qualifications.*.gpa' => 'nullable|numeric',
            'academic_qualifications.*.major' => 'nullable|string',
            'program_id' => 'required|exists:programs,id',
            'guardian_name' => 'required|string|min:2',
            'guardian_phone' => 'required|string|min:10',
            'guardian_email' => 'nullable|email',
            'guardian_relationship' => 'required|string|min:2',
            'guardian_address' => 'required|string|min:5',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $program = Program::findOrFail($validated['program_id']);

        DB::beginTransaction();

        try {
            $registration = Registration::create([
                'user_id' => $request->user()->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'national_id' => $validated['national_id'],
                'national_id_type' => $validated['national_id_type'] ?? 'national_id',
                'national_id_expiry_date' => $validated['national_id_expiry_date'] ?? null,
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'country' => $validated['country'],
                'program_id' => $validated['program_id'],
                'program_name' => $program->name,
                'department' => $program->department ? $program->department->name : 'N/A',
                'intake' => 'Main Intake', // Default or removed from DB later
                'study_mode' => 'full_time', // Default or removed from DB later
                'guardian_name' => $validated['guardian_name'],
                'guardian_phone' => $validated['guardian_phone'],
                'guardian_email' => $validated['guardian_email'] ?? '',
                'guardian_relationship' => $validated['guardian_relationship'],
                'guardian_address' => $validated['guardian_address'],
                'status' => 'pending',
                'submitted_at' => now(),
            ]);

            // Create academic qualifications
            foreach ($validated['academic_qualifications'] as $qual) {
                AcademicQualification::create([
                    'registration_id' => $registration->id,
                    'level' => $qual['level'],
                    'institution_name' => $qual['institution_name'],
                    'institution_address' => $qual['institution_address'],
                    'country' => $qual['country'],
                    'start_date' => $qual['start_date'],
                    'end_date' => $qual['end_date'],
                    'examination_board' => $qual['examination_board'] ?? null,
                    'index_number' => $qual['index_number'] ?? null,
                    'grade' => $qual['grade'] ?? null,
                    'gpa' => $qual['gpa'] ?? null,
                    'major' => $qual['major'] ?? null,
                ]);
            }

            // Handle document uploads
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    $path = $file->store('documents/' . $registration->id, 'local');
                    Document::create([
                        'registration_id' => $registration->id,
                        'type' => 'other',
                        'original_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            // Auto-generate Semester 1 Bills from the configured Fees table
            $applicableFees = \App\Models\Fee::where('is_active', true)
                ->where(function($query) use ($registration) {
                    $query->whereNull('program_id')
                          ->orWhere('program_id', $registration->program_id);
                })
                ->get();

            foreach ($applicableFees as $feeModel) {
                // Generate a bill if semester 1 amount is greater than 0
                if ($feeModel->semester_1_amount > 0) {
                    $controlNumber = 'CN' . now()->year . str_pad(random_int(1, 999999), 6, '0', STR_PAD_LEFT);
                    
                    // Map fee type logic based on name and type
                    $feeType = 'other';
                    $typeLower = strtolower($feeModel->type);
                    $nameLower = strtolower($feeModel->name);
                    
                    if (str_contains($typeLower, 'tuition') || str_contains($nameLower, 'tuition')) $feeType = 'tuition_fee';
                    else if (str_contains($nameLower, 'registration')) $feeType = 'registration_fee';
                    else if (str_contains($typeLower, 'accommodation') || str_contains($typeLower, 'hostel') || str_contains($nameLower, 'accomodation')) $feeType = 'hostel_fee';
                    else if (str_contains($typeLower, 'examination') || str_contains($nameLower, 'examination')) $feeType = 'examination_fee';
                    
                    Payment::create([
                        'registration_id' => $registration->id,
                        'student_id' => $registration->user_id,
                        'fee_type' => $feeType,
                        'description' => $feeModel->name . ' (Semester 1)',
                        'amount' => $feeModel->semester_1_amount,
                        'currency' => $feeModel->currency ?? 'TSH',
                        'control_number' => $controlNumber,
                        'method' => 'bank_transfer', // Default pending method
                        'status' => 'pending',
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Registration submitted successfully',
                'registration' => $registration->load(['academicQualifications', 'documents', 'payments']),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            file_put_contents(storage_path('logs/my_error.log'), $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $registration = Registration::with(['academicQualifications', 'payments', 'documents', 'user'])
            ->findOrFail($id);

        // Check authorization
        if ($request->user()->isStudent() && $registration->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($registration);
    }

    public function approve(Request $request, $id)
    {
        // if (!$request->user()->isAdmin() && !$request->user()->isAccountant()) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        $registration = Registration::findOrFail($id);

        $registrationNumber = 'ZMS-' . date('y') . '-01-' . str_pad($registration->user_id, 4, '0', STR_PAD_LEFT);

        DB::beginTransaction();
        try {
            $registration->update([
                'status' => 'approved',
                'registration_number' => $registrationNumber,
                'approved_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Registration approved successfully',
                'registration' => $registration,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            file_put_contents(storage_path('logs/my_error.log'), 'Registration Approval Failed: ' . $e->getMessage() . "\n" . $e->getTraceAsString(), FILE_APPEND);
            return response()->json(['message' => 'Failed to approve registration', 'error' => $e->getMessage()], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'reason' => 'required|string|min:5',
        ]);

        $registration = Registration::findOrFail($id);

        $registration->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['reason'],
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Registration rejected',
            'registration' => $registration,
        ]);
    }

    public function update(Request $request, $id)
    {
        $registration = Registration::findOrFail($id);

        // Simple validation, assuming these are the fields being sent
        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email',
            'phone' => 'sometimes|required|string|min:9',
            'address' => 'sometimes|required|string',
            'city' => 'sometimes|required|string',
            'country' => 'sometimes|required|string',
            'guardian_name' => 'sometimes|required|string',
            'guardian_phone' => 'sometimes|required|string',
        ]);

        $registration->update($validated);

        return response()->json([
            'message' => 'Registration updated successfully',
            'registration' => $registration,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $registration = Registration::findOrFail($id);
        
        // Optional: Check authorization here, e.g., if isAdmin()
        // if (!$request->user()->isAdmin()) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        $registration->delete();

        return response()->json([
            'message' => 'Registration deleted successfully',
        ]);
    }
}
