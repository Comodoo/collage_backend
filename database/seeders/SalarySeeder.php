<?php

namespace Database\Seeders;

use App\Models\HrProfile;
use App\Models\SalaryAllowance;
use App\Models\SalaryGrade;
use App\Models\SalaryStep;
use App\Models\StaffSalaryPlacement;
use App\Models\User;
use Illuminate\Database\Seeder;

class SalarySeeder extends Seeder
{
    public function run(): void
    {
        $grade = SalaryGrade::firstOrCreate(
            ['code' => 'G1'],
            ['name' => 'Grade 1', 'description' => 'Entry level', 'is_active' => true]
        );

        $step1 = SalaryStep::firstOrCreate(
            ['salary_grade_id' => $grade->id, 'step_number' => 1],
            ['basic_salary' => 500000, 'is_active' => true]
        );
        $step2 = SalaryStep::firstOrCreate(
            ['salary_grade_id' => $grade->id, 'step_number' => 2],
            ['basic_salary' => 600000, 'is_active' => true]
        );

        SalaryAllowance::firstOrCreate(
            ['code' => 'HOUSE'],
            ['name' => 'House Allowance', 'type' => 'fixed', 'amount' => 100000, 'applies_to' => 'all', 'is_active' => true]
        );
        SalaryAllowance::firstOrCreate(
            ['code' => 'TRANSPORT'],
            ['name' => 'Transport Allowance', 'type' => 'percentage', 'percentage' => 10, 'applies_to' => 'all', 'is_active' => true]
        );

        $admin = User::where('email', 'admin@college.edu')->first();
        $profiles = HrProfile::all();

        foreach ($profiles as $profile) {
            StaffSalaryPlacement::firstOrCreate(
                ['hr_profile_id' => $profile->id, 'effective_from' => now()->startOfYear()->toDateString()],
                [
                    'salary_grade_id' => $grade->id,
                    'salary_step_id' => $step1->id,
                    'basic_salary' => $step1->basic_salary,
                    'effective_to' => null,
                    'remarks' => 'Seeded placement',
                    'created_by' => $admin?->id,
                ]
            );
        }
    }
}