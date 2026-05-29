<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = Program::with('department')->where('is_active', true);

        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        return response()->json($query->get());
    }

    public function show($id)
    {
        $program = Program::with('department')->findOrFail($id);
        return response()->json($program);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:programs',
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'duration' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'requirements' => 'nullable|array',
            'tuition_fee' => 'required|numeric|min:0',
            'fees' => 'nullable|array',
            'currency' => 'required|string|max:10',
            'is_active' => 'boolean',
        ]);

        $program = Program::create($request->all());
        return response()->json($program->load('department'), 201);
    }

    public function update(Request $request, $id)
    {
        $program = Program::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:programs,code,' . $id,
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'duration' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'requirements' => 'nullable|array',
            'tuition_fee' => 'required|numeric|min:0',
            'fees' => 'nullable|array',
            'currency' => 'required|string|max:10',
            'is_active' => 'boolean',
        ]);

        $program->update($request->all());
        return response()->json($program->load('department'));
    }

    public function destroy($id)
    {
        $program = Program::findOrFail($id);
        
        if ($program->registrations()->exists()) {
            throw ValidationException::withMessages([
                'message' => ['Cannot delete program with associated registrations.'],
            ]);
        }

        $program->delete();
        return response()->json(['message' => 'Program deleted successfully']);
    }

    public function departments()
    {
        $departments = Department::where('is_active', true)->get();
        return response()->json($departments);
    }

    // Department CRUD
    public function storeDepartment(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:departments',
            'name' => 'required|string|max:255',
            'head_of_department' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $department = Department::create([
            'code' => $request->code,
            'name' => $request->name,
            'head_of_department' => $request->head_of_department,
            'description' => $request->description,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json($department, 201);
    }

    public function updateDepartment(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:departments,code,' . $id,
            'name' => 'required|string|max:255',
            'head_of_department' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $department->update([
            'code' => $request->code,
            'name' => $request->name,
            'head_of_department' => $request->head_of_department,
            'description' => $request->description,
            'is_active' => $request->is_active ?? $department->is_active,
        ]);

        return response()->json($department);
    }

    public function destroyDepartment($id)
    {
        $department = Department::findOrFail($id);
        
        // Check if department has programs or courses
        if ($department->programs()->exists() || $department->courses()->exists()) {
            throw ValidationException::withMessages([
                'message' => ['Cannot delete department with associated programs or courses.'],
            ]);
        }

        $department->delete();
        return response()->json(['message' => 'Department deleted successfully']);
    }
}
