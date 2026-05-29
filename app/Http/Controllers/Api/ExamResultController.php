<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CourseOffering;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamResultController extends Controller
{
    public function index(Request $request)
    {
        $query = ExamResult::with(['student', 'courseOffering.course']);

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('course_offering_id')) {
            $query->where('course_offering_id', $request->course_offering_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->user()->isStudent()) {
            $query->where('student_id', $request->user()->id)
                   ->where('status', 'published');
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        if (!$request->user()->isInstructor() && !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'course_offering_id' => 'required|exists:course_offerings,id',
            'cat1_score' => 'nullable|numeric|min:0|max:100',
            'cat2_score' => 'nullable|numeric|min:0|max:100',
            'assignment_score' => 'nullable|numeric|min:0|max:100',
            'final_exam_score' => 'required|numeric|min:0|max:100',
            'remarks' => 'nullable|string',
        ]);

        // Calculate total score and grade
        $cat1 = $validated['cat1_score'] ?? 0;
        $cat2 = $validated['cat2_score'] ?? 0;
        $assignment = $validated['assignment_score'] ?? 0;
        $final = $validated['final_exam_score'];

        // Weighted calculation: CAT1 (10%) + CAT2 (10%) + Assignment (10%) + Final (70%)
        $totalScore = ($cat1 * 0.1) + ($cat2 * 0.1) + ($assignment * 0.1) + ($final * 0.7);

        $gradeData = $this->calculateGrade($totalScore);

        $result = ExamResult::create([
            'student_id' => $validated['student_id'],
            'course_offering_id' => $validated['course_offering_id'],
            'cat1_score' => $cat1,
            'cat2_score' => $cat2,
            'assignment_score' => $assignment,
            'final_exam_score' => $final,
            'total_score' => $totalScore,
            'grade' => $gradeData['grade'],
            'gpa_points' => $gradeData['gpa'],
            'status' => 'draft',
            'remarks' => $validated['remarks'] ?? null,
            'recorded_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Exam result recorded successfully',
            'result' => $result->load(['student', 'courseOffering.course']),
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $result = ExamResult::with(['student', 'courseOffering.course', 'recorder'])->findOrFail($id);

        // Students can only see published results
        if ($request->user()->isStudent() && 
            ($result->student_id !== $request->user()->id || $result->status !== 'published')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($result);
    }

    public function update(Request $request, $id)
    {
        if (!$request->user()->isInstructor() && !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $result = ExamResult::findOrFail($id);

        if ($result->status === 'published' && !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Cannot modify published results'], 403);
        }

        $validated = $request->validate([
            'cat1_score' => 'nullable|numeric|min:0|max:100',
            'cat2_score' => 'nullable|numeric|min:0|max:100',
            'assignment_score' => 'nullable|numeric|min:0|max:100',
            'final_exam_score' => 'nullable|numeric|min:0|max:100',
            'remarks' => 'nullable|string',
        ]);

        // Recalculate if scores changed
        if (isset($validated['cat1_score']) || isset($validated['cat2_score']) || 
            isset($validated['assignment_score']) || isset($validated['final_exam_score'])) {
            
            $cat1 = $validated['cat1_score'] ?? $result->cat1_score ?? 0;
            $cat2 = $validated['cat2_score'] ?? $result->cat2_score ?? 0;
            $assignment = $validated['assignment_score'] ?? $result->assignment_score ?? 0;
            $final = $validated['final_exam_score'] ?? $result->final_exam_score;

            $totalScore = ($cat1 * 0.1) + ($cat2 * 0.1) + ($assignment * 0.1) + ($final * 0.7);
            $gradeData = $this->calculateGrade($totalScore);

            $validated['total_score'] = $totalScore;
            $validated['grade'] = $gradeData['grade'];
            $validated['gpa_points'] = $gradeData['gpa'];
        }

        $result->update($validated);

        return response()->json([
            'message' => 'Exam result updated successfully',
            'result' => $result->load(['student', 'courseOffering.course']),
        ]);
    }

    public function publish(Request $request, $id)
    {
        if (!$request->user()->isInstructor() && !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $result = ExamResult::findOrFail($id);

        $result->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return response()->json([
            'message' => 'Exam result published successfully',
            'result' => $result,
        ]);
    }

    public function getStudentResults(Request $request, $studentId = null)
    {
        $targetStudentId = $studentId ?? $request->user()->id;

        // Authorization check
        if ($studentId && $request->user()->isStudent() && $studentId != $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $results = ExamResult::with('courseOffering.course')
            ->where('student_id', $targetStudentId)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate GPA
        $totalPoints = $results->sum('gpa_points');
        $gpa = $results->count() > 0 ? $totalPoints / $results->count() : 0;

        return response()->json([
            'results' => $results,
            'summary' => [
                'total_courses' => $results->count(),
                'gpa' => round($gpa, 2),
                'total_points' => $totalPoints,
            ],
        ]);
    }

    public function getCourseResults(Request $request, $courseOfferingId)
    {
        if (!$request->user()->isInstructor() && !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $results = ExamResult::with('student')
            ->where('course_offering_id', $courseOfferingId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($results);
    }

    private function calculateGrade($score)
    {
        if ($score >= 70) return ['grade' => 'A', 'gpa' => 5.0];
        if ($score >= 60) return ['grade' => 'B+', 'gpa' => 4.0];
        if ($score >= 50) return ['grade' => 'B', 'gpa' => 3.0];
        if ($score >= 40) return ['grade' => 'C', 'gpa' => 2.0];
        if ($score >= 35) return ['grade' => 'D', 'gpa' => 1.0];
        return ['grade' => 'E', 'gpa' => 0.0];
    }
}
