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
        // Create admin user
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@college.edu',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '0712345678',
        ]);

        // Create instructor user
        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'instructor@college.edu',
            'password' => Hash::make('password123'),
            'role' => 'instructor',
            'phone' => '0723456789',
        ]);

        // Create student user
        User::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'student@college.edu',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'phone' => '0734567890',
            'date_of_birth' => '2000-05-15',
            'gender' => 'female',
        ]);

        // Create accountant user
        User::create([
            'first_name' => 'Mary',
            'last_name' => 'Johnson',
            'email' => 'accountant@college.edu',
            'password' => Hash::make('password123'),
            'role' => 'accountant',
            'phone' => '0745678901',
        ]);

        // Create departments
        $departments = [
            ['code' => 'CS', 'name' => 'Computer Science', 'head_of_department' => 'Dr. James Wilson'],
            ['code' => 'BUS', 'name' => 'Business Administration', 'head_of_department' => 'Prof. Sarah Johnson'],
            ['code' => 'ENG', 'name' => 'Engineering', 'head_of_department' => 'Dr. Michael Brown'],
            ['code' => 'MED', 'name' => 'Medicine', 'head_of_department' => 'Prof. Emily Davis'],
            ['code' => 'ART', 'name' => 'Arts & Humanities', 'head_of_department' => 'Dr. Robert Taylor'],
        ];

        foreach ($departments as $deptData) {
            Department::create($deptData);
        }

        // Create programs
        $programs = [
            [
                'code' => 'BSC-CS',
                'name' => 'Bachelor of Science in Computer Science',
                'department_id' => 1,
                'duration' => 4,
                'description' => 'A comprehensive program in computer science and software engineering.',
                'tuition_fee' => 150000,
            ],
            [
                'code' => 'BBA',
                'name' => 'Bachelor of Business Administration',
                'department_id' => 2,
                'duration' => 4,
                'description' => 'Business management and administration program.',
                'tuition_fee' => 140000,
            ],
            [
                'code' => 'BENG',
                'name' => 'Bachelor of Engineering',
                'department_id' => 3,
                'duration' => 5,
                'description' => 'Engineering program with multiple specializations.',
                'tuition_fee' => 180000,
            ],
            [
                'code' => 'MBBS',
                'name' => 'Bachelor of Medicine and Surgery',
                'department_id' => 4,
                'duration' => 6,
                'description' => 'Medical degree program.',
                'tuition_fee' => 250000,
            ],
            [
                'code' => 'BA-ENG',
                'name' => 'Bachelor of Arts in English',
                'department_id' => 5,
                'duration' => 3,
                'description' => 'English literature and language program.',
                'tuition_fee' => 120000,
            ],
        ];

        foreach ($programs as $progData) {
            Program::create($progData);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@college.edu / password123');
        $this->command->info('Instructor: instructor@college.edu / password123');
        $this->command->info('Student: student@college.edu / password123');
        $this->command->info('Accountant: accountant@college.edu / password123');
    }
}
