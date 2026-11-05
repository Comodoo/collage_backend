<?php

namespace App\Http\Controllers\Api\Salary;

use App\Http\Controllers\Controller;
use App\Http\Requests\Salary\StoreStaffSalaryPlacementRequest;
use App\Http\Requests\Salary\UpdateStaffSalaryPlacementRequest;
use App\Http\Resources\Salary\StaffSalaryPlacementResource;
use App\Models\StaffSalaryPlacement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StaffSalaryPlacementController extends Controller
{
    private function perPage(Request $request): int
    {
        return min((int) $request->input('per_page', 20), 500) ?: 20;
    }

    public function index(Request $request)
    {
        if (!$request->user()->canAction('salary_scales', 'view')) {
            abort(403);
        }

        $query = StaffSalaryPlacement::with(['profile.user', 'profile.department', 'grade', 'step']);

        if ($request->filled('hr_profile_id')) {
            $query->where('hr_profile_id', $request->input('hr_profile_id'));
        }

        if ($request->filled('salary_grade_id')) {
            $query->where('salary_grade_id', $request->input('salary_grade_id'));
        }

        return StaffSalaryPlacementResource::collection($query->orderByDesc('effective_from')->paginate($this->perPage($request)));
    }

    public function store(StoreStaffSalaryPlacementRequest $request)
    {
        if (!$request->user()->canAction('salary_scales', 'assign')) {
            abort(403);
        }

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $newStart = Carbon::parse($data['effective_from']);

        // Close previous active placement.
        StaffSalaryPlacement::where('hr_profile_id', $data['hr_profile_id'])
            ->where(function ($q) use ($newStart) {
                $q->whereNull('effective_to')
                    ->orWhere('effective_to', '>=', $newStart->toDateString());
            })
            ->update(['effective_to' => $newStart->copy()->subDay()->toDateString()]);

        $placement = StaffSalaryPlacement::create($data);

        return new StaffSalaryPlacementResource($placement->load(['profile.user', 'profile.department', 'grade', 'step']));
    }

    public function update(UpdateStaffSalaryPlacementRequest $request, int $id)
    {
        $placement = StaffSalaryPlacement::findOrFail($id);
        $placement->update($request->validated());

        return new StaffSalaryPlacementResource($placement->fresh(['profile.user', 'profile.department', 'grade', 'step']));
    }
}