<?php

namespace App\Http\Controllers\Api\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreInventoryCategoryRequest;
use App\Http\Resources\Inventory\InventoryCategoryResource;
use App\Models\InventoryCategory;
use Illuminate\Http\Request;

class InventoryCategoryController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()->canAction('inventory', 'view')) {
            abort(403);
        }

        $query = InventoryCategory::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        return InventoryCategoryResource::collection($query->orderBy('name')->paginate($this->perPage($request)));
    }

    public function store(StoreInventoryCategoryRequest $request)
    {
        $category = InventoryCategory::create($request->validated());

        return new InventoryCategoryResource($category);
    }

    private function perPage(Request $request): int
    {
        return min((int) $request->input('per_page', 100), 500) ?: 100;
    }
}