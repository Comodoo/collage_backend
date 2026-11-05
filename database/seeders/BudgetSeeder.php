<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\BudgetCategory;
use App\Models\BudgetExpenditure;
use App\Models\BudgetLine;
use App\Models\Department;
use App\Models\FiscalYear;
use App\Models\User;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    public function run(): void
    {
        $fy = FiscalYear::firstOrCreate(
            ['name' => '2026/2027'],
            ['start_date' => '2026-07-01', 'end_date' => '2027-06-30', 'status' => 'open']
        );

        $categories = [
            ['name' => 'Office Supplies', 'code' => 'OFFICE', 'description' => 'Stationery and supplies'],
            ['name' => 'Equipment', 'code' => 'EQUIP', 'description' => 'Equipment purchases'],
            ['name' => 'Travel', 'code' => 'TRAVEL', 'description' => 'Travel and transport'],
        ];

        $catIds = [];
        foreach ($categories as $data) {
            $cat = BudgetCategory::firstOrCreate(['code' => $data['code']], $data);
            $catIds[$data['code']] = $cat->id;
        }

        $department = Department::first();
        if (!$department) {
            return;
        }

        $admin = User::where('email', 'admin@college.edu')->first();

        $budget = Budget::firstOrCreate(
            ['title' => 'Operations Department Budget', 'fiscal_year_id' => $fy->id, 'department_id' => $department->id],
            [
                'description' => 'Sample operational budget',
                'status' => 'approved',
                'total_requested_amount' => 1000000,
                'total_approved_amount' => 900000,
                'submitted_by' => $admin?->id,
                'submitted_at' => now(),
                'approved_by' => $admin?->id,
                'approved_at' => now(),
            ]
        );

        $line = BudgetLine::firstOrCreate(
            ['budget_id' => $budget->id, 'name' => 'Office Stationery'],
            [
                'budget_category_id' => $catIds['OFFICE'],
                'description' => 'Stationery for the year',
                'quantity' => 100,
                'unit_cost' => 5000,
                'requested_amount' => 500000,
                'approved_amount' => 450000,
                'spent_amount' => 0,
                'status' => 'approved',
            ]
        );

        BudgetExpenditure::firstOrCreate(
            ['budget_line_id' => $line->id, 'reference_number' => 'EXP-001'],
            [
                'expenditure_date' => now()->toDateString(),
                'description' => 'Purchased stationery',
                'amount' => 100000,
                'payment_method' => 'bank_transfer',
                'status' => 'verified',
                'recorded_by' => $admin?->id,
                'verified_by' => $admin?->id,
                'verified_at' => now(),
            ]
        );

        // Update spent amount
        $line->spent_amount = 100000;
        $line->save();
    }
}