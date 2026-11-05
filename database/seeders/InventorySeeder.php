<?php

namespace Database\Seeders;

use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use App\Models\InventoryLocation;
use App\Models\InventoryTransaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Furniture', 'code' => 'FURN', 'description' => 'Office and classroom furniture'],
            ['name' => 'IT Equipment', 'code' => 'ITEQ', 'description' => 'Computers and accessories'],
            ['name' => 'Lab Equipment', 'code' => 'LAB', 'description' => 'Laboratory equipment'],
        ];

        $categoryIds = [];
        foreach ($categories as $data) {
            $category = InventoryCategory::firstOrCreate(['code' => $data['code']], $data);
            $categoryIds[$data['code']] = $category->id;
        }

        $locations = [
            ['name' => 'Main Store', 'code' => 'STORE', 'building' => 'Administration Block', 'room' => 'G01'],
            ['name' => 'Computer Lab', 'code' => 'LAB1', 'building' => 'Science Block', 'room' => '101'],
            ['name' => 'Library', 'code' => 'LIB', 'building' => 'Library Block', 'room' => 'L01'],
        ];

        $locationIds = [];
        foreach ($locations as $data) {
            $location = InventoryLocation::firstOrCreate(['code' => $data['code']], $data);
            $locationIds[$data['code']] = $location->id;
        }

        $admin = User::where('email', 'admin@college.edu')->first() ?? User::first();

        $items = [
            [
                'asset_tag' => 'ZMC-IT-001',
                'name' => 'Dell Desktop Computer',
                'description' => 'Core i5, 8GB RAM',
                'category_code' => 'ITEQ',
                'location_code' => 'LAB1',
                'quantity' => 10,
                'unit' => 'unit',
                'condition' => 'new',
                'status' => 'available',
                'serial_number' => 'SN-DELL-001',
            ],
            [
                'asset_tag' => 'ZMC-FN-001',
                'name' => 'Office Desk',
                'description' => 'Wooden office desk',
                'category_code' => 'FURN',
                'location_code' => 'STORE',
                'quantity' => 5,
                'unit' => 'unit',
                'condition' => 'good',
                'status' => 'available',
            ],
            [
                'asset_tag' => 'ZMC-LB-001',
                'name' => 'Microscope',
                'description' => 'Biology lab microscope',
                'category_code' => 'LAB',
                'location_code' => 'LAB1',
                'quantity' => 3,
                'unit' => 'unit',
                'condition' => 'good',
                'status' => 'available',
            ],
        ];

        foreach ($items as $data) {
            $item = InventoryItem::firstOrCreate(
                ['asset_tag' => $data['asset_tag']],
                [
                    'name' => $data['name'],
                    'description' => $data['description'] ?? null,
                    'category_id' => $categoryIds[$data['category_code']],
                    'location_id' => $locationIds[$data['location_code']],
                    'quantity' => $data['quantity'],
                    'unit' => $data['unit'],
                    'condition' => $data['condition'],
                    'status' => $data['status'],
                    'serial_number' => $data['serial_number'] ?? null,
                    'created_by' => $admin?->id,
                    'updated_by' => $admin?->id,
                ]
            );

            if ($item->wasRecentlyCreated) {
                InventoryTransaction::create([
                    'inventory_item_id' => $item->id,
                    'transaction_type' => 'received',
                    'to_location_id' => $item->location_id,
                    'quantity' => $item->quantity,
                    'condition' => $item->condition,
                    'remarks' => 'Seed data receipt',
                    'transaction_date' => now(),
                    'recorded_by' => $admin?->id ?? 1,
                ]);
            }
        }
    }
}