<?php

namespace App\Http\Controllers\Api\Budget;

use App\Http\Controllers\Controller;
use App\Http\Requests\Budget\StoreBudgetCategoryRequest;
use App\Http\Resources\Budget\BudgetCategoryResource;
use App\Models\BudgetCategory;
use Illuminate\Http\Request;

class BudgetCategoryController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()->canAction('budgets', 'view')) {
            abort(403);
        }

        return BudgetCategoryResource::collection(BudgetCategory::orderBy('name')->paginate($this->perPage($request)));
    }

    public function store(StoreBudgetCategoryRequest $request)
    {
        $cat = BudgetCategory::create($request->validated());

        return new BudgetCategoryResource($cat);
    }

    private function perPage(Request $request): int
    {
        return min((int) $request->input('per_page', 50), 500) ?: 50;
    }
}