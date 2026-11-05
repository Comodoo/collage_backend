<?php

namespace App\Http\Controllers\Api\Budget;

use App\Http\Controllers\Controller;
use App\Http\Requests\Budget\StoreBudgetExpenditureRequest;
use App\Http\Resources\Budget\BudgetExpenditureResource;
use App\Models\BudgetExpenditure;
use App\Models\BudgetLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BudgetExpenditureController extends Controller
{
    private function perPage(Request $request): int
    {
        return min((int) $request->input('per_page', 20), 500) ?: 20;
    }

    public function index(Request $request, int $lineId)
    {
        if (!$request->user()->canAction('budgets', 'view')) {
            abort(403);
        }

        $line = BudgetLine::findOrFail($lineId);

        return BudgetExpenditureResource::collection($line->expenditures()->orderByDesc('expenditure_date')->paginate($this->perPage($request)));
    }

    public function store(StoreBudgetExpenditureRequest $request, int $lineId)
    {
        $line = BudgetLine::with('budget')->findOrFail($lineId);

        if ($line->status !== 'approved' && $line->budget->status !== 'approved') {
            return response()->json(['message' => 'Expenditures can only be recorded against approved budget lines.'], 422);
        }

        $amount = (float) $request->input('amount');
        $remaining = (float) $line->approved_amount - (float) $line->spent_amount;

        if ($amount > $remaining) {
            return response()->json(['message' => 'Expenditure exceeds approved amount.'], 422);
        }

        $expenditure = BudgetExpenditure::create([
            'budget_line_id' => $line->id,
            'expenditure_date' => $request->input('expenditure_date'),
            'description' => $request->input('description'),
            'amount' => $amount,
            'reference_number' => $request->input('reference_number'),
            'payment_method' => $request->input('payment_method'),
            'status' => 'recorded',
            'recorded_by' => $request->user()->id,
        ]);

        return new BudgetExpenditureResource($expenditure);
    }

    public function verify(Request $request, int $id)
    {
        if (!$request->user()->canAction('budgets', 'approve')) {
            abort(403);
        }

        $expenditure = BudgetExpenditure::findOrFail($id);

        DB::transaction(function () use ($expenditure, $request) {
            $expenditure->update([
                'status' => 'verified',
                'verified_by' => $request->user()->id,
                'verified_at' => now(),
            ]);

            $line = $expenditure->line;
            $line->spent_amount = (float) $line->spent_amount + (float) $expenditure->amount;
            $line->save();
        });

        return new BudgetExpenditureResource($expenditure->fresh());
    }
}