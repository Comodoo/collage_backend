<?php

namespace App\Http\Controllers\Api\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreInventoryLocationRequest;
use App\Http\Resources\Inventory\InventoryLocationResource;
use App\Models\InventoryLocation;
use Illuminate\Http\Request;

class InventoryLocationController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()->canAction('inventory', 'view')) {
            abort(403);
        }

        $query = InventoryLocation::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('building', 'like', "%{$search}%")
                    ->orWhere('room', 'like', "%{$search}%");
            });
        }

        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        return InventoryLocationResource::collection($query->orderBy('name')->paginate($this->perPage($request)));
    }

    public function store(StoreInventoryLocationRequest $request)
    {
        $location = InventoryLocation::create($request->validated());

        return new InventoryLocationResource($location);
    }

    private function perPage(Request $request): int
    {
        return min((int) $request->input('per_page', 100), 500) ?: 100;
    }
}