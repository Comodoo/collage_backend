<?php

namespace App\Http\Controllers\Api\Salary;

use App\Http\Controllers\Controller;
use App\Http\Requests\Salary\UpdateSalaryStepRequest;
use App\Http\Resources\Salary\SalaryStepResource;
use App\Models\SalaryStep;
use Illuminate\Http\Request;

class SalaryStepController extends Controller
{
    public function update(UpdateSalaryStepRequest $request, int $id)
    {
        $step = SalaryStep::findOrFail($id);
        $step->update($request->validated());

        return new SalaryStepResource($step->fresh());
    }
}