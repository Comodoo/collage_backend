<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_date', 'location_id', 'inventory_item_id', 'expected_quantity',
        'counted_quantity', 'variance', 'condition', 'remarks', 'conducted_by',
    ];

    protected $casts = [
        'audit_date' => 'date',
        'expected_quantity' => 'integer',
        'counted_quantity' => 'integer',
        'variance' => 'integer',
    ];

    public function location()
    {
        return $this->belongsTo(InventoryLocation::class, 'location_id');
    }

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function conductor()
    {
        return $this->belongsTo(User::class, 'conducted_by');
    }
}