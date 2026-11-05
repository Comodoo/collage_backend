<?php

namespace App\Http\Controllers\Api\Budget;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\BudgetLine;
use Illuminate\Http\Request;

class BudgetReportController extends Controller
{
    public function variance(Request $request)
    {
        if (!$request->user()->canAction('budgets', 'view')) {
            abort(403);
        }

        $query = BudgetLine::with(['budget.department', 'budget.fiscalYear', 'category']);

        if ($request->filled('budget_id')) {
            $query->where('budget_id', $request->input('budget_id'));
        }

        if ($request->filled('department_id')) {
            $query->whereHas('budget', function ($q) use ($request) {
                $q->where('department_id', $request->input('department_id'));
            });
        }

        if ($request->filled('fiscal_year_id')) {
            $query->whereHas('budget', function ($q) use ($request) {
                $q->where('fiscal_year_id', $request->input('fiscal_year_id'));
            });
        }

        $lines = $query->get();

        $rows = $lines->map(function ($line) {
            $approved = (float) $line->approved_amount;
            $spent = (float) $line->spent_amount;

            return [
                'budget_id' => $line->budget_id,
                'budget_title' => $line->budget?->title,
                'department' => $line->budget?->department?->name,
                'fiscal_year' => $line->budget?->fiscalYear?->name,
                'category' => $line->category?->name,
                'line' => $line->name,
                'requested_amount' => (float) $line->requested_amount,
                'approved_amount' => $approved,
                'spent_amount' => $spent,
                'variance' => $approved - $spent,
                'utilization_percentage' => $approved > 0 ? round(($spent / $approved) * 100, 2) : 0,
            ];
        });

        return response()->json(['data' => $rows]);
    }

    public function departmentSummary(Request $request)
    {
        if (!$request->user()->canAction('budgets', 'view')) {
            abort(403);
        }

        $query = Budget::with(['department', 'fiscalYear', 'lines']);

        if ($request->filled('fiscal_year_id')) {
            $query->where('fiscal_year_id', $request->input('fiscal_year_id'));
        }

        $budgets = $query->get();

        $summary = [];
        foreach ($budgets as $budget) {
            $dept = $budget->department?->name ?? 'Unknown';
            if (!isset($summary[$dept])) {
                $summary[$dept] = [
                    'department' => $dept,
                    'budgets' => 0,
                    'requested' => 0,
                    'approved' => 0,
                    'spent' => 0,
                ];
            }

            $summary[$dept]['budgets']++;
            $summary[$dept]['requested'] += (float) $budget->total_requested_amount;
            $summary[$dept]['approved'] += (float) $budget->total_approved_amount;
            $summary[$dept]['spent'] += (float) $budget->lines->sum('spent_amount');
        }

        foreach ($summary as &$row) {
            $row['variance'] = $row['approved'] - $row['spent'];
            $row['utilization_percentage'] = $row['approved'] > 0 ? round(($row['spent'] / $row['approved']) * 100, 2) : 0;
        }

        return response()->json(['data' => array_values($summary)]);
    }
}