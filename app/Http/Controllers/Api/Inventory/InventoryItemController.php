<?php

namespace App\Http\Controllers\Api\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\AssignInventoryRequest;
use App\Http\Requests\Inventory\DisposeInventoryRequest;
use App\Http\Requests\Inventory\ReceiveInventoryRequest;
use App\Http\Requests\Inventory\ReturnInventoryRequest;
use App\Http\Requests\Inventory\StoreInventoryAuditRequest;
use App\Http\Requests\Inventory\StoreInventoryItemRequest;
use App\Http\Requests\Inventory\TransferInventoryRequest;
use App\Http\Requests\Inventory\UpdateInventoryItemRequest;
use App\Http\Resources\Inventory\InventoryAuditResource;
use App\Http\Resources\Inventory\InventoryItemResource;
use App\Http\Resources\Inventory\InventoryTransactionResource;
use App\Models\InventoryAudit;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryItemController extends Controller
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

        $query = InventoryItem::with(['category', 'location', 'custodian']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('asset_tag', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        foreach (['category_id', 'location_id', 'custodian_id'] as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->input($filter));
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->input('condition'));
        }

        return InventoryItemResource::collection($query->orderByDesc('id')->paginate($this->perPage($request)));
    }

    public function show(Request $request, int $id)
    {
        if (!$request->user()->canAction('inventory', 'view')) {
            abort(403);
        }

        $item = InventoryItem::with(['category', 'location', 'custodian', 'creator', 'updater', 'transactions.fromLocation', 'transactions.toLocation', 'transactions.fromUser', 'transactions.toUser', 'transactions.recorder', 'audits.location', 'audits.conductor'])
            ->findOrFail($id);

        return new InventoryItemResource($item);
    }

    public function store(StoreInventoryItemRequest $request)
    {
        $data = $request->validated();
        $userId = $request->user()->id;

        $item = DB::transaction(function () use ($data, $userId) {
            $item = InventoryItem::create(array_merge($data, [
                'created_by' => $userId,
                'updated_by' => $userId,
            ]));

            InventoryTransaction::create([
                'inventory_item_id' => $item->id,
                'transaction_type' => 'received',
                'to_location_id' => $item->location_id,
                'to_user_id' => null,
                'quantity' => $item->quantity,
                'condition' => $item->condition,
                'remarks' => 'Initial receipt',
                'transaction_date' => now(),
                'recorded_by' => $userId,
            ]);

            return $item;
        });

        return new InventoryItemResource($item->load(['category', 'location', 'custodian']));
    }

    public function update(UpdateInventoryItemRequest $request, int $id)
    {
        $item = InventoryItem::findOrFail($id);
        $data = $request->validated();

        $item->update(array_merge($data, ['updated_by' => $request->user()->id]));

        return new InventoryItemResource($item->fresh(['category', 'location', 'custodian']));
    }

    public function destroy(Request $request, int $id)
    {
        if (!$request->user()->canAction('inventory', 'delete')) {
            abort(403);
        }

        $item = InventoryItem::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Inventory item deleted.']);
    }

    public function receive(ReceiveInventoryRequest $request, int $id)
    {
        $item = InventoryItem::findOrFail($id);
        $data = $request->validated();
        $userId = $request->user()->id;

        $item = DB::transaction(function () use ($item, $data, $userId) {
            $oldLocation = $item->location_id;

            $item->quantity += $data['quantity'];
            if (isset($data['location_id'])) {
                $item->location_id = $data['location_id'];
            }
            if (isset($data['condition'])) {
                $item->condition = $data['condition'];
            }
            if (!in_array($item->status, ['lost', 'disposed'])) {
                $item->status = 'available';
            }
            $item->updated_by = $userId;
            $item->save();

            InventoryTransaction::create([
                'inventory_item_id' => $item->id,
                'transaction_type' => 'received',
                'from_location_id' => $oldLocation !== $item->location_id ? $oldLocation : null,
                'to_location_id' => $item->location_id,
                'quantity' => $data['quantity'],
                'condition' => $item->condition,
                'remarks' => $data['remarks'] ?? null,
                'transaction_date' => now(),
                'recorded_by' => $userId,
            ]);

            return $item;
        });

        return new InventoryItemResource($item->fresh(['category', 'location', 'custodian']));
    }

    public function assign(AssignInventoryRequest $request, int $id)
    {
        $item = InventoryItem::findOrFail($id);

        if (in_array($item->status, ['lost', 'disposed', 'under_repair'])) {
            return response()->json(['message' => 'Item cannot be assigned in its current status.'], 422);
        }

        $data = $request->validated();
        $userId = $request->user()->id;

        $item = DB::transaction(function () use ($item, $data, $userId) {
            $fromUser = $item->custodian_id;

            $item->custodian_id = $data['to_user_id'];
            $item->status = 'assigned';
            $item->updated_by = $userId;
            $item->save();

            InventoryTransaction::create([
                'inventory_item_id' => $item->id,
                'transaction_type' => 'assigned',
                'from_user_id' => $fromUser,
                'to_user_id' => $data['to_user_id'],
                'from_location_id' => $item->location_id,
                'to_location_id' => $item->location_id,
                'quantity' => $data['quantity'] ?? $item->quantity,
                'condition' => $item->condition,
                'remarks' => $data['remarks'] ?? null,
                'transaction_date' => now(),
                'recorded_by' => $userId,
            ]);

            return $item;
        });

        return new InventoryItemResource($item->fresh(['category', 'location', 'custodian']));
    }

    public function transfer(TransferInventoryRequest $request, int $id)
    {
        $item = InventoryItem::findOrFail($id);

        if (in_array($item->status, ['lost', 'disposed'])) {
            return response()->json(['message' => 'Item cannot be transferred in its current status.'], 422);
        }

        $data = $request->validated();
        $userId = $request->user()->id;

        $item = DB::transaction(function () use ($item, $data, $userId) {
            $fromLocation = $item->location_id;

            $item->location_id = $data['to_location_id'];
            $item->updated_by = $userId;
            $item->save();

            InventoryTransaction::create([
                'inventory_item_id' => $item->id,
                'transaction_type' => 'transferred',
                'from_location_id' => $fromLocation,
                'to_location_id' => $data['to_location_id'],
                'quantity' => $data['quantity'] ?? $item->quantity,
                'condition' => $item->condition,
                'remarks' => $data['remarks'] ?? null,
                'transaction_date' => now(),
                'recorded_by' => $userId,
            ]);

            return $item;
        });

        return new InventoryItemResource($item->fresh(['category', 'location', 'custodian']));
    }

    public function returnItem(ReturnInventoryRequest $request, int $id)
    {
        $item = InventoryItem::findOrFail($id);
        $data = $request->validated();
        $userId = $request->user()->id;

        $item = DB::transaction(function () use ($item, $data, $userId) {
            $fromUser = $item->custodian_id;

            $item->custodian_id = null;
            $item->status = 'available';
            if (isset($data['location_id'])) {
                $item->location_id = $data['location_id'];
            }
            if (isset($data['condition'])) {
                $item->condition = $data['condition'];
            }
            $item->updated_by = $userId;
            $item->save();

            InventoryTransaction::create([
                'inventory_item_id' => $item->id,
                'transaction_type' => 'returned',
                'from_user_id' => $fromUser,
                'to_location_id' => $item->location_id,
                'quantity' => $data['quantity'] ?? $item->quantity,
                'condition' => $item->condition,
                'remarks' => $data['remarks'] ?? null,
                'transaction_date' => now(),
                'recorded_by' => $userId,
            ]);

            return $item;
        });

        return new InventoryItemResource($item->fresh(['category', 'location', 'custodian']));
    }

    public function repair(Request $request, int $id)
    {
        if (!$request->user()->canAction('inventory', 'update')) {
            abort(403);
        }

        $item = InventoryItem::findOrFail($id);
        $userId = $request->user()->id;

        $item = DB::transaction(function () use ($item, $userId, $request) {
            $item->status = 'under_repair';
            $item->updated_by = $userId;
            $item->save();

            InventoryTransaction::create([
                'inventory_item_id' => $item->id,
                'transaction_type' => 'repair',
                'to_location_id' => $item->location_id,
                'quantity' => $item->quantity,
                'condition' => $item->condition,
                'remarks' => $request->input('remarks'),
                'transaction_date' => now(),
                'recorded_by' => $userId,
            ]);

            return $item;
        });

        return new InventoryItemResource($item->fresh(['category', 'location', 'custodian']));
    }

    public function markLost(Request $request, int $id)
    {
        if (!$request->user()->canAction('inventory', 'update')) {
            abort(403);
        }

        $item = InventoryItem::findOrFail($id);
        $userId = $request->user()->id;

        $item = DB::transaction(function () use ($item, $userId, $request) {
            $item->status = 'lost';
            $item->quantity = 0;
            $item->updated_by = $userId;
            $item->save();

            InventoryTransaction::create([
                'inventory_item_id' => $item->id,
                'transaction_type' => 'lost',
                'quantity' => $request->input('quantity', 1),
                'condition' => $item->condition,
                'remarks' => $request->input('remarks'),
                'transaction_date' => now(),
                'recorded_by' => $userId,
            ]);

            return $item;
        });

        return new InventoryItemResource($item->fresh(['category', 'location', 'custodian']));
    }

    public function dispose(DisposeInventoryRequest $request, int $id)
    {
        $item = InventoryItem::findOrFail($id);
        $data = $request->validated();
        $userId = $request->user()->id;

        $item = DB::transaction(function () use ($item, $data, $userId) {
            $item->status = 'disposed';
            $item->quantity = 0;
            $item->updated_by = $userId;
            $item->save();

            InventoryTransaction::create([
                'inventory_item_id' => $item->id,
                'transaction_type' => 'disposed',
                'quantity' => $data['quantity'] ?? 1,
                'condition' => $item->condition,
                'remarks' => $data['remarks'] ?? null,
                'transaction_date' => now(),
                'recorded_by' => $userId,
            ]);

            return $item;
        });

        return new InventoryItemResource($item->fresh(['category', 'location', 'custodian']));
    }

    public function transactions(Request $request)
    {
        if (!$request->user()->canAction('inventory', 'view')) {
            abort(403);
        }

        $query = InventoryTransaction::with(['item', 'fromLocation', 'toLocation', 'fromUser', 'toUser', 'recorder']);

        if ($request->filled('inventory_item_id')) {
            $query->where('inventory_item_id', $request->input('inventory_item_id'));
        }

        return InventoryTransactionResource::collection($query->orderByDesc('transaction_date')->orderByDesc('id')->paginate($this->perPage($request)));
    }

    public function export(Request $request)
    {
        if (!$request->user()->canAction('inventory', 'export')) {
            abort(403);
        }

        $items = InventoryItem::with(['category', 'location', 'custodian'])->get();

        $headers = ['Asset Tag', 'Name', 'Category', 'Location', 'Quantity', 'Condition', 'Status', 'Custodian', 'Serial Number'];

        $callback = function () use ($items, $headers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            foreach ($items as $item) {
                fputcsv($out, [
                    $item->asset_tag,
                    $item->name,
                    $item->category?->name,
                    $item->location?->name,
                    $item->quantity,
                    $item->condition,
                    $item->status,
                    $item->custodian?->first_name . ' ' . $item->custodian?->last_name,
                    $item->serial_number,
                ]);
            }
            fclose($out);
        };

        return response()->streamDownload($callback, 'inventory-items.csv', ['Content-Type' => 'text/csv']);
    }

    public function exportPdf(Request $request)
    {
        if (!$request->user()->canAction('inventory', 'export')) {
            abort(403);
        }

        $items = InventoryItem::with(['category', 'location', 'custodian'])->get();

        $html = '<h1>Inventory Report</h1><table border="1" cellpadding="4" cellspacing="0" width="100%"><thead><tr><th>Asset Tag</th><th>Name</th><th>Category</th><th>Location</th><th>Qty</th><th>Status</th></tr></thead><tbody>';
        foreach ($items as $item) {
            $html .= '<tr>'
                . '<td>' . e($item->asset_tag) . '</td>'
                . '<td>' . e($item->name) . '</td>'
                . '<td>' . e($item->category?->name) . '</td>'
                . '<td>' . e($item->location?->name) . '</td>'
                . '<td>' . e($item->quantity) . '</td>'
                . '<td>' . e($item->status) . '</td>'
                . '</tr>';
        }
        $html .= '</tbody></table>';

        return Pdf::loadHTML($html)->download('inventory-items.pdf');
    }

    public function history(Request $request, int $id)
    {
        if (!$request->user()->canAction('inventory', 'view')) {
            abort(403);
        }

        $item = InventoryItem::findOrFail($id);

        $transactions = InventoryTransaction::with(['fromLocation', 'toLocation', 'fromUser', 'toUser', 'recorder'])
            ->where('inventory_item_id', $id)
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->paginate($this->perPage($request));

        return InventoryTransactionResource::collection($transactions);
    }
}