<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CourseOffering;
use App\Models\ExamResult;
use Illuminate\Http\Request;

class ExamResultController extends Controller
{
    public function index(Request $request)
    {
        $query = ExamResult::with(['student', 'courseOffering.course', 'submitter', 'reviewer']);

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
                ->where('status', ExamResult::STATUS_PUBLISHED);
        } elseif ($request->user()->isInstructor()) {
            // Instructors only see results they recorded.
            $query->where('recorded_by', $request->user()->id);
        }

        $perPage = min((int) $request->input('per_page', 100), 500);

        return response()->json($query->orderBy('created_at', 'desc')->paginate($perPage));
    }

    public function store(Request $request)
    {
        if (!$request->user()->isInstructor() && !$request->user()->canAction('results', 'create')) {
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
            'status' => ExamResult::STATUS_DRAFT,
            'remarks' => $validated['remarks'] ?? null,
            'recorded_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Exam result recorded as draft',
            'result' => $result->load(['student', 'courseOffering.course']),
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $result = ExamResult::with(['student', 'courseOffering.course', 'recorder', 'submitter', 'reviewer'])->findOrFail($id);

        if ($request->user()->isStudent()) {
            if ($result->student_id !== $request->user()->id || $result->status !== ExamResult::STATUS_PUBLISHED) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        } elseif ($request->user()->isInstructor()) {
            if ($result->recorded_by !== $request->user()->id && !$request->user()->canAction('results', 'view')) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }

        return response()->json($result);
    }

    public function update(Request $request, $id)
    {
        $result = ExamResult::findOrFail($id);

        if (!$this->canEdit($request->user(), $result)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'cat1_score' => 'nullable|numeric|min:0|max:100',
            'cat2_score' => 'nullable|numeric|min:0|max:100',
            'assignment_score' => 'nullable|numeric|min:0|max:100',
            'final_exam_score' => 'nullable|numeric|min:0|max:100',
            'remarks' => 'nullable|string',
        ]);

        // Recalculate if scores changed
        $scoresChanged = isset($validated['cat1_score']) || isset($validated['cat2_score'])
            || isset($validated['assignment_score']) || isset($validated['final_exam_score']);

        if ($scoresChanged) {
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

        // Editing a rejected result moves it back to draft for resubmission.
        if ($result->isRejected()) {
            $validated['status'] = ExamResult::STATUS_DRAFT;
            $validated['rejection_reason'] = null;
            $validated['submitted_by'] = null;
            $validated['submitted_at'] = null;
            $validated['reviewed_by'] = null;
            $validated['reviewed_at'] = null;
        }

        $result->update($validated);

        return response()->json([
            'message' => 'Exam result updated successfully',
            'result' => $result->load(['student', 'courseOffering.course', 'submitter', 'reviewer']),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $result = ExamResult::findOrFail($id);

        if (!$request->user()->isAdmin()) {
            if (!$request->user()->isInstructor() || $result->recorded_by !== $request->user()->id || !$result->canBeEdited()) {
                return response()->json(['message' => 'Only draft or rejected results can be deleted by the recording instructor'], 403);
            }
        }

        $result->delete();

        return response()->json(['message' => 'Exam result deleted successfully']);
    }

    /**
     * Instructor submits draft/rejected results for admin review. Students cannot see them yet.
     */
    public function submit(Request $request, $id)
    {
        $result = ExamResult::findOrFail($id);

        if (!$request->user()->isAdmin()) {
            if (!$request->user()->isInstructor() || $result->recorded_by !== $request->user()->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }

        if (!$result->canBeEdited()) {
            return response()->json(['message' => 'Only draft or rejected results can be submitted'], 422);
        }

        $result->update([
            'status' => ExamResult::STATUS_SUBMITTED,
            'submitted_by' => $request->user()->id,
            'submitted_at' => now(),
            'rejection_reason' => null,
        ]);

        return response()->json([
            'message' => 'Exam result submitted for approval',
            'result' => $result->load(['student', 'courseOffering.course', 'submitter']),
        ]);
    }

    /**
     * Admin publishes a submitted result. Only then can students see it.
     */
    public function publish(Request $request, $id)
    {
        $result = ExamResult::findOrFail($id);

        if (!$request->user()->isAdmin() && !$request->user()->canAction('results', 'publish')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($result->status !== ExamResult::STATUS_SUBMITTED && $result->status !== ExamResult::STATUS_DRAFT) {
            return response()->json(['message' => 'Only submitted results can be published'], 422);
        }

        $result->update([
            'status' => ExamResult::STATUS_PUBLISHED,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
            'rejection_reason' => null,
            'published_at' => now(),
        ]);

        return response()->json([
            'message' => 'Exam result published. Students can now view it.',
            'result' => $result->load(['student', 'courseOffering.course', 'reviewer']),
        ]);
    }

    /**
     * Admin rejects a submitted result with a reason. It returns to the teacher for correction.
     */
    public function reject(Request $request, $id)
    {
        $result = ExamResult::findOrFail($id);

        if (!$request->user()->isAdmin() && !$request->user()->canAction('results', 'reject')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        if ($result->status !== ExamResult::STATUS_SUBMITTED) {
            return response()->json(['message' => 'Only submitted results can be rejected'], 422);
        }

        $result->update([
            'status' => ExamResult::STATUS_REJECTED,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
            'rejection_reason' => $validated['reason'],
            'published_at' => null,
        ]);

        return response()->json([
            'message' => 'Exam result rejected and returned to the teacher',
            'result' => $result->load(['student', 'courseOffering.course', 'reviewer']),
        ]);
    }

    public function getStudentResults(Request $request, $studentId = null)
    {
        $targetStudentId = $studentId ?? $request->user()->id;

        if ($studentId && $request->user()->isStudent() && $studentId != $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $results = ExamResult::with('courseOffering.course')
            ->where('student_id', $targetStudentId)
            ->where('status', ExamResult::STATUS_PUBLISHED)
            ->orderBy('created_at', 'desc')
            ->get();

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

    private function canEdit($user, ExamResult $result): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (!$user->isInstructor() && !$user->canAction('results', 'update')) {
            return false;
        }

        // Teachers may only edit their own draft/rejected results.
        if ($result->recorded_by !== $user->id) {
            return false;
        }

        return $result->canBeEdited();
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