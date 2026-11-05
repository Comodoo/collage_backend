<?php

namespace App\Http\Controllers\Api\Budget;

use App\Http\Controllers\Controller;
use App\Http\Requests\Budget\StoreFiscalYearRequest;
use App\Http\Resources\Budget\FiscalYearResource;
use App\Models\FiscalYear;
use Illuminate\Http\Request;

class FiscalYearController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()->canAction('budgets', 'view')) {
            abort(403);
        }

        return FiscalYearResource::collection(FiscalYear::orderByDesc('start_date')->paginate($this->perPage($request)));
    }

    public function store(StoreFiscalYearRequest $request)
    {
        $fy = FiscalYear::create($request->validated());

        return new FiscalYearResource($fy);
    }

    private function perPage(Request $request): int
    {
        return min((int) $request->input('per_page', 50), 500) ?: 50;
    }
}