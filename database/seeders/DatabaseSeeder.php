<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Roles & permissions first so staff can be assigned roles below.
        $this->call(RolePermissionSeeder::class);

        // Create the demo accounts. firstOrCreate keeps this seeder re-runnable.
        $admin = User::firstOrCreate(
            ['email' => 'admin@college.edu'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '0712345678',
            ]
        );
        $admin->roles()->syncWithoutDetaching('ADMIN');

        $instructor = User::firstOrCreate(
            ['email' => 'instructor@college.edu'],
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'password' => Hash::make('password123'),
                'role' => 'instructor',
                'phone' => '0723456789',
            ]
        );
        $instructor->roles()->syncWithoutDetaching('EXAM_OFFICER');

        User::firstOrCreate(
            ['email' => 'student@college.edu'],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'phone' => '0734567890',
                'date_of_birth' => '2000-05-15',
                'gender' => 'female',
            ]
        );

        $accountant = User::firstOrCreate(
            ['email' => 'accountant@college.edu'],
            [
                'first_name' => 'Mary',
                'last_name' => 'Johnson',
                'password' => Hash::make('password123'),
                'role' => 'accountant',
                'phone' => '0745678901',
            ]
        );
        $accountant->roles()->syncWithoutDetaching('ACCOUNTANT');

        $operations = User::firstOrCreate(
            ['email' => 'operations@college.edu'],
            [
                'first_name' => 'Operations',
                'last_name' => 'Officer',
                'password' => Hash::make('password123'),
                'role' => 'operations',
                'phone' => '0756789012',
            ]
        );
        $operations->roles()->syncWithoutDetaching('OPERATIONS_PLANNER');

        // Create departments
        $departments = [
            ['code' => 'CS', 'name' => 'Computer Science', 'head_of_department' => 'Dr. James Wilson'],
            ['code' => 'BUS', 'name' => 'Business Administration', 'head_of_department' => 'Prof. Sarah Johnson'],
            ['code' => 'ENG', 'name' => 'Engineering', 'head_of_department' => 'Dr. Michael Brown'],
            ['code' => 'MED', 'name' => 'Medicine', 'head_of_department' => 'Prof. Emily Davis'],
            ['code' => 'ART', 'name' => 'Arts & Humanities', 'head_of_department' => 'Dr. Robert Taylor'],
        ];

        $departmentIds = [];
        foreach ($departments as $deptData) {
            $department = Department::firstOrCreate(
                ['code' => $deptData['code']],
                $deptData
            );
            $departmentIds[$deptData['code']] = $department->id;
        }

        // Create programs (linked by department code, not hardcoded IDs)
        $programs = [
            [
                'code' => 'BSC-CS',
                'name' => 'Bachelor of Science in Computer Science',
                'department_code' => 'CS',
                'duration' => 4,
                'description' => 'A comprehensive program in computer science and software engineering.',
                'tuition_fee' => 150000,
            ],
            [
                'code' => 'BBA',
                'name' => 'Bachelor of Business Administration',
                'department_code' => 'BUS',
                'duration' => 4,
                'description' => 'Business management and administration program.',
                'tuition_fee' => 140000,
            ],
            [
                'code' => 'BENG',
                'name' => 'Bachelor of Engineering',
                'department_code' => 'ENG',
                'duration' => 5,
                'description' => 'Engineering program with multiple specializations.',
                'tuition_fee' => 180000,
            ],
            [
                'code' => 'MBBS',
                'name' => 'Bachelor of Medicine and Surgery',
                'department_code' => 'MED',
                'duration' => 6,
                'description' => 'Medical degree program.',
                'tuition_fee' => 250000,
            ],
            [
                'code' => 'BA-ENG',
                'name' => 'Bachelor of Arts in English',
                'department_code' => 'ART',
                'duration' => 3,
                'description' => 'English literature and language program.',
                'tuition_fee' => 120000,
            ],
        ];

        foreach ($programs as $progData) {
            $departmentCode = $progData['department_code'];
            unset($progData['department_code']);

            Program::firstOrCreate(
                ['code' => $progData['code']],
                ['department_id' => $departmentIds[$departmentCode]] + $progData
            );
        }

        $this->call(InventorySeeder::class);
        $this->call(HrProfileSeeder::class);
        $this->call(SalarySeeder::class);
        $this->call(BudgetSeeder::class);
        $this->call(ProposalSeeder::class);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@college.edu / password123');
        $this->command->info('Instructor: instructor@college.edu / password123');
        $this->command->info('Student: student@college.edu / password123');
        $this->command->info('Accountant: accountant@college.edu / password123');
        $this->command->info('Operations: operations@college.edu / password123');
    }
}
