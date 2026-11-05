<?php

namespace App\Http\Controllers\Api\Operations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Operations\OperationsDashboardRequest;
use App\Http\Resources\Operations\OperationsDashboardResource;

class OperationsDashboardController extends Controller
{
    public function index(OperationsDashboardRequest $request)
    {
        $data = [
            'title' => 'Operations & Planning Dashboard',
            'cards' => [
                ['key' => 'inventory', 'title' => 'Inventory', 'description' => 'Coming in next phase'],
                ['key' => 'hr', 'title' => 'HR', 'description' => 'Coming in next phase'],
                ['key' => 'budgets', 'title' => 'Budgets', 'description' => 'Coming in next phase'],
                ['key' => 'proposals', 'title' => 'Proposals', 'description' => 'Coming in next phase'],
            ],
            'message' => 'Coming in next phase',
        ];

        return new OperationsDashboardResource($data);
    }
}