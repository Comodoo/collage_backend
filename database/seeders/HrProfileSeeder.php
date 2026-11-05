<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\HrProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class HrProfileSeeder extends Seeder
{
    public function run(): void
    {
        $department = Department::first();
        if (!$department) {
            return;
        }

        $users = User::whereIn('role', ['admin', 'instructor', 'accountant', 'operations'])->get();

        foreach ($users as $index => $user) {
            HrProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'employee_number' => 'EMP-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'department_id' => $department->id,
                    'position_title' => ucfirst($user->role) . ' Officer',
                    'employment_type' => 'permanent',
                    'date_of_employment' => now()->subYears(1)->toDateString(),
                    'contract_start_date' => now()->subYears(1)->toDateString(),
                    'contract_end_date' => now()->addDays(45)->toDateString(),
                    'employment_status' => 'active',
                    'qualification_summary' => 'Seeded profile',
                    'emergency_contact_name' => 'Emergency Contact',
                    'emergency_contact_phone' => '0700000000',
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]
            );
        }
    }
}