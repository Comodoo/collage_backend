<?php

namespace App\Http\Controllers\Api\Budget;

use App\Http\Controllers\Controller;
use App\Http\Requests\Budget\UpdateBudgetLineRequest;
use App\Http\Resources\Budget\BudgetLineResource;
use App\Models\BudgetLine;
use Illuminate\Http\Request;

class BudgetLineController extends Controller
{
    public function update(UpdateBudgetLineRequest $request, int $id)
    {
        $line = BudgetLine::findOrFail($id);
        $line->update($request->validated());

        $budget = $line->budget;
        $budget->update([
            'total_requested_amount' => $budget->lines()->sum('requested_amount'),
            'total_approved_amount' => $budget->lines()->sum('approved_amount'),
        ]);

        return new BudgetLineResource($line->fresh('category'));
    }
}