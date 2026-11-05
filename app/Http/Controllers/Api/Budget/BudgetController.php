<?php

namespace App\Http\Controllers\Api\Budget;

use App\Http\Controllers\Controller;
use App\Http\Requests\Budget\StoreBudgetLineRequest;
use App\Http\Requests\Budget\StoreBudgetRequest;
use App\Http\Requests\Budget\UpdateBudgetRequest;
use App\Http\Resources\Budget\BudgetLineResource;
use App\Http\Resources\Budget\BudgetResource;
use App\Models\Budget;
use App\Models\BudgetLine;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    private function perPage(Request $request): int
    {
        return min((int) $request->input('per_page', 20), 500) ?: 20;
    }

    public function index(Request $request)
    {
        if (!$request->user()->canAction('budgets', 'view')) {
            abort(403);
        }

        $query = Budget::with(['fiscalYear', 'department']);

        if ($request->filled('fiscal_year_id')) {
            $query->where('fiscal_year_id', $request->input('fiscal_year_id'));
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return BudgetResource::collection($query->orderByDesc('id')->paginate($this->perPage($request)));
    }

    public function store(StoreBudgetRequest $request)
    {
        if (!$request->user()->canAction('budgets', 'create')) {
            abort(403);
        }

        $budget = Budget::create(array_merge($request->validated(), [
            'status' => 'draft',
        ]));

        return new BudgetResource($budget->load(['fiscalYear', 'department', 'lines']));
    }

    public function show(Request $request, int $id)
    {
        if (!$request->user()->canAction('budgets', 'view')) {
            abort(403);
        }

        $budget = Budget::with(['fiscalYear', 'department', 'lines.category', 'submitter', 'reviewer', 'approver'])->findOrFail($id);

        return new BudgetResource($budget);
    }

    public function update(UpdateBudgetRequest $request, int $id)
    {
        $budget = Budget::findOrFail($id);

        if ($budget->status !== 'draft') {
            return response()->json(['message' => 'Only draft budgets can be edited.'], 422);
        }

        $budget->update($request->validated());

        return new BudgetResource($budget->fresh(['fiscalYear', 'department', 'lines']));
    }

    public function storeLine(StoreBudgetLineRequest $request, int $id)
    {
        $budget = Budget::findOrFail($id);

        if ($budget->status !== 'draft') {
            return response()->json(['message' => 'Lines can only be added to draft budgets.'], 422);
        }

        $line = BudgetLine::create(array_merge($request->validated(), [
            'budget_id' => $budget->id,
            'requested_amount' => $request->input('requested_amount', $request->input('quantity') * $request->input('unit_cost')),
        ]));

        $budget->update([
            'total_requested_amount' => $budget->lines()->sum('requested_amount'),
            'total_approved_amount' => $budget->lines()->sum('approved_amount'),
        ]);

        return new BudgetLineResource($line->load('category'));
    }

    public function submit(Request $request, int $id)
    {
        if (!$request->user()->canAction('budgets', 'submit')) {
            abort(403);
        }

        $budget = Budget::with('lines')->findOrFail($id);

        if ($budget->lines->isEmpty()) {
            return response()->json(['message' => 'Budget cannot be submitted without lines.'], 422);
        }

        $budget->update([
            'status' => 'submitted',
            'submitted_by' => $request->user()->id,
            'submitted_at' => now(),
        ]);

        return new BudgetResource($budget->fresh(['fiscalYear', 'department', 'lines']));
    }

    public function review(Request $request, int $id)
    {
        if (!$request->user()->canAction('budgets', 'review')) {
            abort(403);
        }

        $budget = Budget::findOrFail($id);

        $budget->update([
            'status' => 'under_review',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return new BudgetResource($budget->fresh(['fiscalYear', 'department', 'lines']));
    }

    public function approve(Request $request, int $id)
    {
        if (!$request->user()->canAction('budgets', 'approve')) {
            abort(403);
        }

        $budget = Budget::with('lines')->findOrFail($id);

        foreach ($budget->lines as $line) {
            if ((float) $line->approved_amount == 0) {
                $line->update([
                    'approved_amount' => $line->requested_amount,
                    'status' => 'approved',
                ]);
            } else {
                $line->update(['status' => 'approved']);
            }
        }

        $budget->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'total_approved_amount' => $budget->lines()->sum('approved_amount'),
        ]);

        return new BudgetResource($budget->fresh(['fiscalYear', 'department', 'lines']));
    }

    public function reject(Request $request, int $id)
    {
        if (!$request->user()->canAction('budgets', 'reject')) {
            abort(403);
        }

        $budget = Budget::findOrFail($id);

        $budget->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('reason'),
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        return new BudgetResource($budget->fresh(['fiscalYear', 'department', 'lines']));
    }

    public function close(Request $request, int $id)
    {
        if (!$request->user()->canAction('budgets', 'close')) {
            abort(403);
        }

        $budget = Budget::findOrFail($id);

        $budget->update(['status' => 'closed']);

        $budget->lines()->update(['status' => 'closed']);

        return new BudgetResource($budget->fresh(['fiscalYear', 'department', 'lines']));
    }

    public function export(Request $request)
    {
        if (!$request->user()->canAction('budgets', 'export')) {
            abort(403);
        }

        $budgets = Budget::with(['fiscalYear', 'department'])->get();

        $headers = ['Title', 'Fiscal Year', 'Department', 'Requested', 'Approved', 'Status'];

        $callback = function () use ($budgets, $headers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            foreach ($budgets as $b) {
                fputcsv($out, [
                    $b->title,
                    $b->fiscalYear?->name,
                    $b->department?->name,
                    $b->total_requested_amount,
                    $b->total_approved_amount,
                    $b->status,
                ]);
            }
            fclose($out);
        };

        return response()->streamDownload($callback, 'budgets.csv', ['Content-Type' => 'text/csv']);
    }

    private function recalcTotals(Budget $budget): void
    {
        $budget->update([
            'total_requested_amount' => $budget->lines()->sum('requested_amount'),
        ]);
    }
}