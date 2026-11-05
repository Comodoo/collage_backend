<?php

namespace App\Http\Controllers\Api\Salary;

use App\Http\Controllers\Controller;
use App\Models\SalaryAllowance;
use App\Models\StaffSalaryPlacement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SalaryCostProjectionController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()->canAction('salary_scales', 'view')) {
            abort(403);
        }

        $fiscalYear = (int) $request->input('fiscal_year', now()->year);
        $yearStart = Carbon::create($fiscalYear, 1, 1)->startOfDay();
        $yearEnd = Carbon::create($fiscalYear, 12, 31)->endOfDay();

        $query = StaffSalaryPlacement::with(['profile.user', 'profile.department', 'grade', 'step']);

        if ($request->filled('department_id')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('department_id', $request->input('department_id'));
            });
        }

        if ($request->filled('salary_grade_id')) {
            $query->where('salary_grade_id', $request->input('salary_grade_id'));
        }

        if ($request->filled('employment_type')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('employment_type', $request->input('employment_type'));
            });
        }

        // Active during fiscal year.
        $query->where('effective_from', '<=', $yearEnd)
            ->where(function ($q) use ($yearStart) {
                $q->whereNull('effective_to')
                    ->orWhere('effective_to', '>=', $yearStart);
            });

        $placements = $query->get();
        $allowances = SalaryAllowance::where('is_active', true)->get();

        $totals = [
            'staff_count' => 0,
            'total_basic' => 0,
            'total_allowances' => 0,
            'monthly_cost' => 0,
            'annual_cost' => 0,
        ];

        $byDepartment = [];
        $byGrade = [];
        $byType = [];

        foreach ($placements as $placement) {
            $profile = $placement->profile;
            if (!$profile) {
                continue;
            }

            $basic = (float) $placement->basic_salary;
            $allowanceTotal = 0;

            foreach ($allowances as $allowance) {
                $applicable = false;
                if ($allowance->applies_to === 'all') {
                    $applicable = true;
                } elseif ($allowance->applies_to === 'salary_grade' && (int) $allowance->salary_grade_id === (int) $placement->salary_grade_id) {
                    $applicable = true;
                } elseif ($allowance->applies_to === 'employment_type' && $allowance->employment_type === $profile->employment_type) {
                    $applicable = true;
                }

                if (!$applicable) {
                    continue;
                }

                if ($allowance->type === 'fixed') {
                    $allowanceTotal += (float) ($allowance->amount ?? 0);
                } else {
                    $pct = (float) ($allowance->percentage ?? 0);
                    $allowanceTotal += $basic * ($pct / 100);
                }
            }

            $monthly = $basic + $allowanceTotal;
            $annual = $monthly * 12;

            $totals['staff_count']++;
            $totals['total_basic'] += $basic;
            $totals['total_allowances'] += $allowanceTotal;
            $totals['monthly_cost'] += $monthly;
            $totals['annual_cost'] += $annual;

            $deptName = $profile->department?->name ?? 'Unknown';
            if (!isset($byDepartment[$deptName])) {
                $byDepartment[$deptName] = ['department' => $deptName, 'staff_count' => 0, 'monthly_cost' => 0, 'annual_cost' => 0];
            }
            $byDepartment[$deptName]['staff_count']++;
            $byDepartment[$deptName]['monthly_cost'] += $monthly;
            $byDepartment[$deptName]['annual_cost'] += $annual;

            $gradeName = $placement->grade?->name ?? 'Unknown';
            if (!isset($byGrade[$gradeName])) {
                $byGrade[$gradeName] = ['grade' => $gradeName, 'staff_count' => 0, 'monthly_cost' => 0, 'annual_cost' => 0];
            }
            $byGrade[$gradeName]['staff_count']++;
            $byGrade[$gradeName]['monthly_cost'] += $monthly;
            $byGrade[$gradeName]['annual_cost'] += $annual;

            $typeName = $profile->employment_type ?? 'Unknown';
            if (!isset($byType[$typeName])) {
                $byType[$typeName] = ['employment_type' => $typeName, 'staff_count' => 0, 'monthly_cost' => 0, 'annual_cost' => 0];
            }
            $byType[$typeName]['staff_count']++;
            $byType[$typeName]['monthly_cost'] += $monthly;
            $byType[$typeName]['annual_cost'] += $annual;
        }

        return response()->json([
            'filters' => [
                'department_id' => $request->input('department_id'),
                'salary_grade_id' => $request->input('salary_grade_id'),
                'employment_type' => $request->input('employment_type'),
                'fiscal_year' => $fiscalYear,
            ],
            'summary' => $totals,
            'by_department' => array_values($byDepartment),
            'by_grade' => array_values($byGrade),
            'by_employment_type' => array_values($byType),
        ]);
    }
}