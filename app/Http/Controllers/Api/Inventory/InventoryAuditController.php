<?php

namespace App\Http\Controllers\Api\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\StoreInventoryAuditRequest;
use App\Http\Resources\Inventory\InventoryAuditResource;
use App\Models\InventoryAudit;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryAuditController extends Controller
{
    private function perPage(Request $request): int
    {
        return min((int) $request->input('per_page', 20), 500) ?: 20;
    }

    public function index(Request $request)
    {
        if (!$request->user()->canAction('inventory', 'view')) {
            abort(403);
        }

        $query = InventoryAudit::with(['location', 'item', 'conductor']);

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->input('location_id'));
        }

        if ($request->filled('inventory_item_id')) {
            $query->where('inventory_item_id', $request->input('inventory_item_id'));
        }

        return InventoryAuditResource::collection($query->orderByDesc('audit_date')->orderByDesc('id')->paginate($this->perPage($request)));
    }

    public function store(StoreInventoryAuditRequest $request)
    {
        $data = $request->validated();
        $userId = $request->user()->id;

        $variance = $data['counted_quantity'] - $data['expected_quantity'];

        $audit = DB::transaction(function () use ($data, $userId, $variance) {
            $audit = InventoryAudit::create(array_merge($data, [
                'variance' => $variance,
                'conducted_by' => $userId,
            ]));

            $item = InventoryItem::find($data['inventory_item_id']);
            if ($item) {
                $diff = $variance;
                if ($diff !== 0) {
                    $item->quantity = max(0, $item->quantity + $diff);
                    if (isset($data['condition'])) {
                        $item->condition = $data['condition'];
                    }
                    $item->updated_by = $userId;
                    $item->save();

                    InventoryTransaction::create([
                        'inventory_item_id' => $item->id,
                        'transaction_type' => 'audit_adjustment',
                        'to_location_id' => $item->location_id,
                        'quantity' => $diff,
                        'condition' => $item->condition,
                        'remarks' => $data['remarks'] ?? 'Audit adjustment',
                        'transaction_date' => now(),
                        'recorded_by' => $userId,
                    ]);
                }
            }

            return $audit;
        });

        return new InventoryAuditResource($audit->load(['location', 'item', 'conductor']));
    }
}