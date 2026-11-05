<?php

namespace App\Http\Controllers\Api\Operations;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\BudgetExpenditure;
use App\Models\BudgetLine;
use App\Models\HrLeaveRecord;
use App\Models\HrProfile;
use App\Models\InventoryAudit;
use App\Models\InventoryItem;
use App\Models\Payment;
use App\Models\Proposal;
use App\Models\ProposalActivity;
use App\Models\SalaryAllowance;
use App\Models\StaffSalaryPlacement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class OperationsReportController extends Controller
{
    private function dateRange(Request $request): array
    {
        $from = $request->filled('date_from') ? Carbon::parse($request->input('date_from'))->startOfDay() : null;
        $to = $request->filled('date_to') ? Carbon::parse($request->input('date_to'))->endOfDay() : null;

        return [$from, $to];
    }

    public function dashboard(Request $request)
    {
        if (!$request->user()->canAction('reports', 'operations.view')) {
            abort(403);
        }

        [$from, $to] = $this->dateRange($request);

        $inventoryQuery = InventoryItem::withTrashed();
        $items = $inventoryQuery->get();
        $inventoryByStatus = $items->groupBy('status')->map->count();

        $hrQuery = HrProfile::query();
        if ($request->filled('department_id')) {
            $hrQuery->where('department_id', $request->input('department_id'));
        }
        $hrProfiles = $hrQuery->get();
        $hrByStatus = $hrProfiles->groupBy('employment_status')->map->count();

        $expiringSoon = $hrProfiles->filter(function ($p) {
            return $p->contract_end_date && $p->contract_end_date->between(now(), now()->addDays(90));
        })->count();

        $budgetQuery = Budget::with('lines');
        if ($request->filled('department_id')) {
            $budgetQuery->where('department_id', $request->input('department_id'));
        }
        $budgets = $budgetQuery->get();
        $approvedBudgets = $budgets->where('status', 'approved');
        $totalRequested = $budgets->sum('total_requested_amount');
        $totalApproved = $approvedBudgets->sum('total_approved_amount');
        $totalSpent = $approvedBudgets->flatMap->lines->sum('spent_amount');
        $pendingApprovals = $budgets->whereIn('status', ['submitted', 'under_review'])->count();

        $proposalQuery = Proposal::query();
        if ($request->filled('department_id')) {
            $proposalQuery->where('department_id', $request->input('department_id'));
        }
        $proposals = $proposalQuery->get();
        $proposalByStatus = $proposals->groupBy('status')->map->count();
        $delayedActivities = ProposalActivity::where('status', 'delayed')->count();

        return response()->json([
            'inventory' => [
                'total' => $items->count(),
                'by_status' => $inventoryByStatus,
            ],
            'hr' => [
                'total' => $hrProfiles->count(),
                'by_status' => $hrByStatus,
                'contracts_expiring_90_days' => $expiringSoon,
            ],
            'budgets' => [
                'total_requested' => (float) $totalRequested,
                'total_approved' => (float) $totalApproved,
                'total_spent' => (float) $totalSpent,
                'remaining' => (float) ($totalApproved - $totalSpent),
                'utilization_percentage' => $totalApproved > 0 ? round(($totalSpent / $totalApproved) * 100, 2) : 0,
                'pending_approvals' => $pendingApprovals,
            ],
            'proposals' => [
                'total' => $proposals->count(),
                'by_status' => $proposalByStatus,
                'delayed_activities' => $delayedActivities,
            ],
        ]);
    }

    public function inventorySummary(Request $request)
    {
        if (!$request->user()->canAction('reports', 'operations.view')) {
            abort(403);
        }

        $items = InventoryItem::with(['category', 'location', 'custodian'])->withTrashed()->get();

        $byCategory = $items->groupBy(fn ($i) => $i->category?->name ?? 'Unknown')->map(function ($group) {
            return ['count' => $group->count(), 'quantity' => $group->sum('quantity')];
        });

        $byLocation = $items->groupBy(fn ($i) => $i->location?->name ?? 'Unknown')->map(function ($group) {
            return ['count' => $group->count(), 'quantity' => $group->sum('quantity')];
        });

        $byCondition = $items->groupBy('condition')->map->count();
        $byStatus = $items->groupBy('status')->map->count();

        $assignedToStaff = $items->filter(fn ($i) => $i->custodian_id)->values();
        $lostDisposed = $items->whereIn('status', ['lost', 'disposed'])->values();

        $audits = InventoryAudit::all();
        $auditVariance = [
            'total_audits' => $audits->count(),
            'total_variance' => $audits->sum('variance'),
            'positive_variance' => $audits->where('variance', '>', 0)->sum('variance'),
            'negative_variance' => $audits->where('variance', '<', 0)->sum('variance'),
        ];

        return response()->json([
            'by_category' => $byCategory,
            'by_location' => $byLocation,
            'by_condition' => $byCondition,
            'by_status' => $byStatus,
            'assigned_to_staff' => $assignedToStaff,
            'lost_and_disposed' => $lostDisposed,
            'audit_variance' => $auditVariance,
        ]);
    }

    public function hrSummary(Request $request)
    {
        if (!$request->user()->canAction('reports', 'operations.view')) {
            abort(403);
        }

        $query = HrProfile::with(['department', 'user']);
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }
        $profiles = $query->get();

        $activeByDepartment = $profiles->where('employment_status', 'active')
            ->groupBy(fn ($p) => $p->department?->name ?? 'Unknown')
            ->map->count();

        $byType = $profiles->groupBy('employment_type')->map->count();

        $contractExpiry = $profiles->filter(function ($p) {
            return $p->contract_end_date && $p->contract_end_date->between(now(), now()->addDays(90));
        })->values();

        $leaveSummary = [
            'pending' => HrLeaveRecord::where('status', 'pending')->count(),
            'approved' => HrLeaveRecord::where('status', 'approved')->count(),
            'rejected' => HrLeaveRecord::where('status', 'rejected')->count(),
            'cancelled' => HrLeaveRecord::where('status', 'cancelled')->count(),
        ];

        $statusSummary = $profiles->groupBy('employment_status')->map->count();

        return response()->json([
            'active_by_department' => $activeByDepartment,
            'by_employment_type' => $byType,
            'contract_expiry' => $contractExpiry,
            'leave_summary' => $leaveSummary,
            'employment_status_summary' => $statusSummary,
        ]);
    }

    public function budgetSummary(Request $request)
    {
        if (!$request->user()->canAction('reports', 'operations.view')) {
            abort(403);
        }

        [$from, $to] = $this->dateRange($request);

        $budgetQuery = Budget::with(['department', 'fiscalYear', 'lines']);
        if ($request->filled('department_id')) {
            $budgetQuery->where('department_id', $request->input('department_id'));
        }
        if ($request->filled('status')) {
            $budgetQuery->where('status', $request->input('status'));
        }

        $budgets = $budgetQuery->get();
        $approved = $budgets->where('status', 'approved');

        $requested = $budgets->sum('total_requested_amount');
        $approvedAmount = $approved->sum('total_approved_amount');
        $spent = $approved->flatMap->lines->sum('spent_amount');

        $varianceByDepartment = [];
        foreach ($budgets as $b) {
            $dept = $b->department?->name ?? 'Unknown';
            if (!isset($varianceByDepartment[$dept])) {
                $varianceByDepartment[$dept] = ['department' => $dept, 'requested' => 0, 'approved' => 0, 'spent' => 0];
            }
            $varianceByDepartment[$dept]['requested'] += (float) $b->total_requested_amount;
            $varianceByDepartment[$dept]['approved'] += (float) $b->total_approved_amount;
            $varianceByDepartment[$dept]['spent'] += (float) $b->lines->sum('spent_amount');
        }
        foreach ($varianceByDepartment as &$row) {
            $row['variance'] = $row['approved'] - $row['spent'];
            $row['utilization_percentage'] = $row['approved'] > 0 ? round(($row['spent'] / $row['approved']) * 100, 2) : 0;
        }

        $varianceByCategory = [];
        foreach ($budgets as $b) {
            foreach ($b->lines as $line) {
                $cat = $line->category?->name ?? 'Unknown';
                if (!isset($varianceByCategory[$cat])) {
                    $varianceByCategory[$cat] = ['category' => $cat, 'requested' => 0, 'approved' => 0, 'spent' => 0];
                }
                $varianceByCategory[$cat]['requested'] += (float) $line->requested_amount;
                $varianceByCategory[$cat]['approved'] += (float) $line->approved_amount;
                $varianceByCategory[$cat]['spent'] += (float) $line->spent_amount;
            }
        }
        foreach ($varianceByCategory as &$row) {
            $row['variance'] = $row['approved'] - $row['spent'];
            $row['utilization_percentage'] = $row['approved'] > 0 ? round(($row['spent'] / $row['approved']) * 100, 2) : 0;
        }

        $expenditureQuery = BudgetExpenditure::with(['line.budget.department']);
        if ($from) {
            $expenditureQuery->whereDate('expenditure_date', '>=', $from);
        }
        if ($to) {
            $expenditureQuery->whereDate('expenditure_date', '<=', $to);
        }
        $expenditures = $expenditureQuery->orderByDesc('expenditure_date')->get();

        $unspent = [];
        foreach ($budgets->where('status', 'approved') as $b) {
            foreach ($b->lines as $line) {
                $remaining = (float) $line->approved_amount - (float) $line->spent_amount;
                if ($remaining > 0) {
                    $unspent[] = [
                        'budget' => $b->title,
                        'department' => $b->department?->name,
                        'line' => $line->name,
                        'approved' => (float) $line->approved_amount,
                        'spent' => (float) $line->spent_amount,
                        'remaining' => $remaining,
                    ];
                }
            }
        }

        return response()->json([
            'summary' => [
                'total_requested' => (float) $requested,
                'total_approved' => (float) $approvedAmount,
                'total_spent' => (float) $spent,
                'remaining' => (float) ($approvedAmount - $spent),
                'utilization_percentage' => $approvedAmount > 0 ? round(($spent / $approvedAmount) * 100, 2) : 0,
            ],
            'variance_by_department' => array_values($varianceByDepartment),
            'variance_by_category' => array_values($varianceByCategory),
            'expenditure_register' => $expenditures,
            'unspent_budget' => $unspent,
        ]);
    }

    public function proposalSummary(Request $request)
    {
        if (!$request->user()->canAction('reports', 'operations.view')) {
            abort(403);
        }

        $query = Proposal::with(['department', 'activities', 'kpis']);
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        $proposals = $query->get();

        $byType = $proposals->groupBy('type')->map->count();
        $byStatus = $proposals->groupBy('status')->map->count();

        $budgetTotals = [
            'total_budget' => (float) $proposals->sum('total_budget_amount'),
            'approved_budget' => (float) $proposals->where('status', 'approved')->sum('total_budget_amount') + (float) $proposals->where('status', 'published')->sum('total_budget_amount'),
        ];

        $implementationProgress = $proposals->map(function ($p) {
            $activities = $p->activities;
            return $activities->count() > 0 ? $activities->avg('progress_percentage') : 0;
        });
        $avgProgress = $implementationProgress->count() > 0 ? round($implementationProgress->avg(), 2) : 0;

        $kpiSummary = [];
        foreach ($proposals as $p) {
            foreach ($p->kpis as $kpi) {
                $kpiSummary[] = [
                    'proposal' => $p->reference_number,
                    'kpi' => $kpi->name,
                    'target' => $kpi->target_value,
                    'actual' => $kpi->actual_value,
                    'unit' => $kpi->unit,
                    'status' => $kpi->status,
                ];
            }
        }

        $delayedActivities = ProposalActivity::where('status', 'delayed')->count();

        return response()->json([
            'by_type' => $byType,
            'by_status' => $byStatus,
            'budget_totals' => $budgetTotals,
            'implementation_progress' => $avgProgress,
            'kpi_summary' => $kpiSummary,
            'delayed_activities' => $delayedActivities,
        ]);
    }

    public function financialSummary(Request $request)
    {
        if (!$request->user()->canAction('reports', 'financial.view')) {
            abort(403);
        }

        [$from, $to] = $this->dateRange($request);

        $paymentQuery = Payment::query();
        if ($from) {
            $paymentQuery->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $paymentQuery->whereDate('created_at', '<=', $to);
        }
        $payments = $paymentQuery->get();

        $verifiedPayments = $payments->where('status', 'completed');
        $unverifiedPayments = $payments->where('status', '!=', 'completed');

        $studentFeeIncome = (float) $verifiedPayments->sum('amount');

        // Outstanding fees: registrations without completed payment? Simple: total payments pending.
        $outstandingFees = (float) $payments->where('status', 'pending')->sum('amount');

        $budgetExpenditure = (float) BudgetExpenditure::where('status', 'verified')->sum('amount');

        $salaryProjection = StaffSalaryPlacement::with('profile')
            ->where(function ($q) {
                $q->whereNull('effective_to')
                    ->orWhere('effective_to', '>=', now()->toDateString());
            })
            ->get();
        $monthlySalary = 0;
        foreach ($salaryProjection as $placement) {
            $monthlySalary += (float) $placement->basic_salary;
        }
        // Add allowances? Simple: basic only for projection summary.
        $annualSalary = $monthlySalary * 12;

        $allowances = SalaryAllowance::where('is_active', true)->get();
        // We'll compute total allowances monthly across placements.
        $monthlyAllowances = 0;
        foreach ($salaryProjection as $placement) {
            $basic = (float) $placement->basic_salary;
            foreach ($allowances as $allowance) {
                $applicable = false;
                if ($allowance->applies_to === 'all') {
                    $applicable = true;
                } elseif ($allowance->applies_to === 'salary_grade' && (int) $allowance->salary_grade_id === (int) $placement->salary_grade_id) {
                    $applicable = true;
                } elseif ($allowance->applies_to === 'employment_type' && $allowance->employment_type === $placement->profile?->employment_type) {
                    $applicable = true;
                }

                if (!$applicable) {
                    continue;
                }

                if ($allowance->type === 'fixed') {
                    $monthlyAllowances += (float) ($allowance->amount ?? 0);
                } else {
                    $monthlyAllowances += $basic * ((float) ($allowance->percentage ?? 0) / 100);
                }
            }
        }

        $totalMonthlySalary = $monthlySalary + $monthlyAllowances;
        $totalAnnualSalary = $totalMonthlySalary * 12;

        $income = $studentFeeIncome;
        $expenditure = $budgetExpenditure + $totalAnnualSalary;
        $surplus = $income - $expenditure;

        return response()->json([
            'student_fee_income' => $studentFeeIncome,
            'verified_payments' => (float) $verifiedPayments->sum('amount'),
            'unverified_payments' => (float) $unverifiedPayments->sum('amount'),
            'outstanding_fees' => $outstandingFees,
            'budget_expenditure' => $budgetExpenditure,
            'salary_cost' => [
                'monthly_basic' => $monthlySalary,
                'monthly_allowances' => $monthlyAllowances,
                'monthly_total' => $totalMonthlySalary,
                'annual_total' => $totalAnnualSalary,
            ],
            'income_vs_expenditure' => [
                'income' => $income,
                'expenditure' => $expenditure,
                'surplus_deficit' => $surplus,
            ],
            'payment_status_breakdown' => $payments->groupBy('status')->map->count(),
        ]);
    }

    public function hrSummaryPdf(Request $request)
    {
        if (!$request->user()->canAction('reports', 'operations.view')) {
            abort(403);
        }

        $profiles = HrProfile::with('department')->get();
        $activeByDept = $profiles->where('employment_status', 'active')
            ->groupBy(fn ($p) => $p->department?->name ?? 'Unknown')
            ->map->count();
        $byType = $profiles->groupBy('employment_type')->map->count();

        $html = '<h1>HR Summary Report</h1>';
        $html .= '<h2>Active Staff by Department</h2><table border="1" cellpadding="4" width="100%"><tr><th>Department</th><th>Count</th></tr>';
        foreach ($activeByDept as $dept => $count) {
            $html .= '<tr><td>' . e($dept) . '</td><td>' . $count . '</td></tr>';
        }
        $html .= '</table>';

        $html .= '<h2>Staff by Employment Type</h2><table border="1" cellpadding="4" width="100%"><tr><th>Type</th><th>Count</th></tr>';
        foreach ($byType as $type => $count) {
            $html .= '<tr><td>' . e($type) . '</td><td>' . $count . '</td></tr>';
        }
        $html .= '</table>';

        return Pdf::loadHTML($html)->download('hr-summary.pdf');
    }

    public function budgetSummaryPdf(Request $request)
    {
        if (!$request->user()->canAction('reports', 'operations.view')) {
            abort(403);
        }

        $budgets = Budget::with(['department', 'lines'])->get();
        $requested = $budgets->sum('total_requested_amount');
        $approved = $budgets->where('status', 'approved')->sum('total_approved_amount');
        $spent = $budgets->where('status', 'approved')->flatMap->lines->sum('spent_amount');

        $html = '<h1>Budget Summary Report</h1>';
        $html .= '<p>Requested: ' . $requested . '</p>';
        $html .= '<p>Approved: ' . $approved . '</p>';
        $html .= '<p>Spent: ' . $spent . '</p>';
        $html .= '<p>Remaining: ' . ($approved - $spent) . '</p>';

        $html .= '<h2>Variance by Department</h2><table border="1" cellpadding="4" width="100%"><tr><th>Department</th><th>Requested</th><th>Approved</th><th>Spent</th></tr>';
        $byDept = [];
        foreach ($budgets as $b) {
            $dept = $b->department?->name ?? 'Unknown';
            if (!isset($byDept[$dept])) {
                $byDept[$dept] = ['requested' => 0, 'approved' => 0, 'spent' => 0];
            }
            $byDept[$dept]['requested'] += (float) $b->total_requested_amount;
            $byDept[$dept]['approved'] += (float) $b->total_approved_amount;
            $byDept[$dept]['spent'] += (float) $b->lines->sum('spent_amount');
        }
        foreach ($byDept as $dept => $row) {
            $html .= '<tr><td>' . e($dept) . '</td><td>' . $row['requested'] . '</td><td>' . $row['approved'] . '</td><td>' . $row['spent'] . '</td></tr>';
        }
        $html .= '</table>';

        return Pdf::loadHTML($html)->download('budget-summary.pdf');
    }

    public function proposalSummaryPdf(Request $request)
    {
        if (!$request->user()->canAction('reports', 'operations.view')) {
            abort(403);
        }

        $proposals = Proposal::with(['department'])->get();
        $byType = $proposals->groupBy('type')->map->count();
        $byStatus = $proposals->groupBy('status')->map->count();

        $html = '<h1>Proposal Summary Report</h1>';
        $html .= '<h2>By Type</h2><table border="1" cellpadding="4" width="100%"><tr><th>Type</th><th>Count</th></tr>';
        foreach ($byType as $type => $count) {
            $html .= '<tr><td>' . e($type) . '</td><td>' . $count . '</td></tr>';
        }
        $html .= '</table>';

        $html .= '<h2>By Status</h2><table border="1" cellpadding="4" width="100%"><tr><th>Status</th><th>Count</th></tr>';
        foreach ($byStatus as $status => $count) {
            $html .= '<tr><td>' . e($status) . '</td><td>' . $count . '</td></tr>';
        }
        $html .= '</table>';

        return Pdf::loadHTML($html)->download('proposal-summary.pdf');
    }
}