<?php

namespace App\Http\Controllers\Api\Salary;

use App\Http\Controllers\Controller;
use App\Http\Requests\Salary\StoreSalaryAllowanceRequest;
use App\Http\Requests\Salary\UpdateSalaryAllowanceRequest;
use App\Http\Resources\Salary\SalaryAllowanceResource;
use App\Models\SalaryAllowance;
use Illuminate\Http\Request;

class SalaryAllowanceController extends Controller
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

        $query = SalaryAllowance::with('grade');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('applies_to')) {
            $query->where('applies_to', $request->input('applies_to'));
        }

        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        return SalaryAllowanceResource::collection($query->orderBy('name')->paginate($this->perPage($request)));
    }

    public function store(StoreSalaryAllowanceRequest $request)
    {
        $allowance = SalaryAllowance::create($request->validated());

        return new SalaryAllowanceResource($allowance->load('grade'));
    }

    public function update(UpdateSalaryAllowanceRequest $request, int $id)
    {
        $allowance = SalaryAllowance::findOrFail($id);
        $allowance->update($request->validated());

        return new SalaryAllowanceResource($allowance->fresh('grade'));
    }
}