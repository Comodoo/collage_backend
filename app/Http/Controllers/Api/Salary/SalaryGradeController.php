<?php

namespace App\Http\Controllers\Api\Salary;

use App\Http\Controllers\Controller;
use App\Http\Requests\Salary\StoreSalaryGradeRequest;
use App\Http\Requests\Salary\StoreSalaryStepRequest;
use App\Http\Requests\Salary\UpdateSalaryGradeRequest;
use App\Http\Resources\Salary\SalaryGradeResource;
use App\Http\Resources\Salary\SalaryStepResource;
use App\Models\SalaryGrade;
use App\Models\SalaryStep;
use Illuminate\Http\Request;

class SalaryGradeController extends Controller
{
    private function perPage(Request $request): int
    {
        return min((int) $request->input('per_page', 50), 500) ?: 50;
    }

    public function index(Request $request)
    {
        if (!$request->user()->canAction('salary_scales', 'view')) {
            abort(403);
        }

        $query = SalaryGrade::with('steps');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        return SalaryGradeResource::collection($query->orderBy('name')->paginate($this->perPage($request)));
    }

    public function store(StoreSalaryGradeRequest $request)
    {
        $grade = SalaryGrade::create($request->validated());

        return new SalaryGradeResource($grade->load('steps'));
    }

    public function update(UpdateSalaryGradeRequest $request, int $id)
    {
        $grade = SalaryGrade::findOrFail($id);
        $grade->update($request->validated());

        return new SalaryGradeResource($grade->fresh('steps'));
    }

    public function steps(Request $request, int $id)
    {
        if (!$request->user()->canAction('salary_scales', 'view')) {
            abort(403);
        }

        $grade = SalaryGrade::findOrFail($id);

        return SalaryStepResource::collection($grade->steps()->orderBy('step_number')->get());
    }

    public function storeStep(StoreSalaryStepRequest $request, int $id)
    {
        $grade = SalaryGrade::findOrFail($id);

        $step = SalaryStep::create(array_merge($request->validated(), [
            'salary_grade_id' => $grade->id,
        ]));

        return new SalaryStepResource($step);
    }
}