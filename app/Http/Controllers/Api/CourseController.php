<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\InstructorAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    // Get all courses
    public function index(Request $request)
    {
        $query = Course::with(['department', 'program', 'credit', 'creator', 'offerings', 'instructorAssignments.instructor']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    // Create a new course (Admin only)
    public function store(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized - Only admins can create courses'], 403);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:courses',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'credit_hours' => 'nullable|integer|min:1|max:20',
            'credit_id' => 'nullable|exists:credits,id',
            'department_id' => 'required|exists:departments,id',
            'program_id' => 'required|exists:programs,id',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $course = Course::create([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'credit_hours' => $request->credit_hours ?? 3,
            'credit_id' => $request->credit_id,
            'department_id' => $request->department_id,
            'program_id' => $request->program_id,
            'status' => $request->status ?? 'active',
            'created_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Course created successfully',
            'course' => $course->load(['department', 'program', 'credit', 'creator']),
        ], 201);
    }

    // Get single course
    public function show(Request $request, $id)
    {
        $course = Course::with(['department', 'program', 'credit', 'creator', 'offerings.instructor', 'instructorAssignments.instructor'])->findOrFail($id);
        return response()->json($course);
    }

    // Update course (Admin only)
    public function update(Request $request, $id)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized - Only admins can update courses'], 403);
        }

        $course = Course::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'code' => 'sometimes|string|max:20|unique:courses,code,' . $id,
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'credit_hours' => 'nullable|integer|min:1|max:20',
            'credit_id' => 'nullable|exists:credits,id',
            'department_id' => 'sometimes|exists:departments,id',
            'program_id' => 'sometimes|exists:programs,id',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $course->update($request->only(['code', 'name', 'description', 'credit_hours', 'credit_id', 'department_id', 'program_id', 'status']));

        return response()->json([
            'message' => 'Course updated successfully',
            'course' => $course->load(['department', 'program', 'credit', 'creator']),
        ]);
    }

    // Delete course (Admin only)
    public function destroy(Request $request, $id)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized - Only admins can delete courses'], 403);
        }

        $course = Course::findOrFail($id);
        $course->delete();

        return response()->json(['message' => 'Course deleted successfully']);
    }

    // Assign instructor to course (Admin only)
    public function assignInstructor(Request $request, $courseId)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized - Only admins can assign instructors'], 403);
        }

        $validator = Validator::make($request->all(), [
            'instructor_id' => 'required|exists:users,id',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|in:semester_one,semester_two',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verify user is an instructor
        $instructor = User::findOrFail($request->instructor_id);
        if (!$instructor->isInstructor()) {
            return response()->json(['message' => 'Selected user is not an instructor'], 400);
        }

        $course = Course::findOrFail($courseId);

        // Check for duplicate assignment
        $existing = InstructorAssignment::where([
            'course_id' => $courseId,
            'instructor_id' => $request->instructor_id,
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'status' => 'active',
        ])->first();

        if ($existing) {
            return response()->json(['message' => 'Instructor already assigned to this course for the selected period'], 400);
        }

        $assignment = InstructorAssignment::create([
            'course_id' => $courseId,
            'instructor_id' => $request->instructor_id,
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'assigned_by' => $request->user()->id,
            'assigned_at' => now(),
            'status' => 'active',
            'notes' => $request->notes,
        ]);

        return response()->json([
            'message' => 'Instructor assigned successfully',
            'assignment' => $assignment->load(['instructor', 'assigner']),
        ], 201);
    }

    // Remove instructor assignment (Admin only)
    public function removeInstructor(Request $request, $courseId, $assignmentId)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $assignment = InstructorAssignment::where('course_id', $courseId)
            ->where('id', $assignmentId)
            ->firstOrFail();

        $assignment->update(['status' => 'inactive']);

        return response()->json(['message' => 'Instructor assignment removed successfully']);
    }

    // Get instructor assignments for a course
    public function getInstructorAssignments(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $assignments = InstructorAssignment::where('course_id', $courseId)
            ->with(['instructor', 'assigner'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($assignments);
    }

    // Get all instructors (for dropdown)
    public function getInstructors(Request $request)
    {
        $instructors = User::where('role', 'instructor')
            ->select('id', 'first_name', 'last_name', 'email')
            ->orderBy('first_name')
            ->get();

        return response()->json($instructors);
    }

    // Get courses assigned to the logged-in instructor
    public function getMyCourses(Request $request)
    {
        $user = $request->user();
        if (!$user->isInstructor()) {
            return response()->json(['message' => 'Unauthorized - Only instructors can view their courses'], 403);
        }

        $courseIds = InstructorAssignment::where('instructor_id', $user->id)
            ->where('status', 'active')
            ->pluck('course_id');

        $courses = Course::whereIn('id', $courseIds)
            ->with(['department', 'credit', 'instructorAssignments' => function($query) use ($user) {
                $query->where('instructor_id', $user->id)->where('status', 'active');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($courses);
    }

    // Update instructor assignment status
    public function updateAssignmentStatus(Request $request, $assignmentId)
    {
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $assignment = InstructorAssignment::findOrFail($assignmentId);
        $assignment->status = $request->status;
        $assignment->save();

        return response()->json([
            'message' => 'Assignment status updated successfully',
            'assignment' => $assignment
        ]);
    }
}
