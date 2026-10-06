<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissionCatalog = [
            ['dashboard', 'view', 'View Dashboard', 'Access the admin dashboard'],
            ['students', 'view', 'View Students', null],
            ['students', 'update', 'Edit Students', null],
            ['students', 'delete', 'Delete Students', null],
            ['staff', 'view', 'View Staff', null],
            ['staff', 'create', 'Add Staff', null],
            ['staff', 'update', 'Edit Staff', null],
            ['staff', 'delete', 'Delete Staff', null],
            ['programs', 'view', 'View Programs', null],
            ['programs', 'create', 'Add Programs', null],
            ['programs', 'update', 'Edit Programs', null],
            ['programs', 'delete', 'Delete Programs', null],
            ['departments', 'view', 'View Departments', null],
            ['departments', 'create', 'Add Departments', null],
            ['departments', 'update', 'Edit Departments', null],
            ['departments', 'delete', 'Delete Departments', null],
            ['courses', 'view', 'View Courses', null],
            ['courses', 'create', 'Add Courses', null],
            ['courses', 'update', 'Edit Courses', null],
            ['courses', 'delete', 'Delete Courses', null],
            ['instructors', 'view', 'View Instructors', null],
            ['instructors', 'assign', 'Assign Instructors', null],
            ['credits', 'view', 'View Credits', null],
            ['credits', 'create', 'Add Credits', null],
            ['credits', 'update', 'Edit Credits', null],
            ['credits', 'delete', 'Delete Credits', null],
            ['fees', 'view', 'View Fees', null],
            ['fees', 'create', 'Add Fees', null],
            ['fees', 'update', 'Edit Fees', null],
            ['fees', 'delete', 'Delete Fees', null],
            ['registrations', 'view', 'View Registrations', null],
            ['registrations', 'create', 'Add Registrations', null],
            ['registrations', 'update', 'Edit Registrations', null],
            ['registrations', 'delete', 'Delete Registrations', null],
            ['registrations', 'approve', 'Approve Registrations', null],
            ['registrations', 'reject', 'Reject Registrations', null],
            ['results', 'view', 'View Results', null],
            ['results', 'create', 'Add Results', null],
            ['results', 'update', 'Edit Results', null],
            ['results', 'delete', 'Delete Results', null],
            ['results', 'publish', 'Publish Results', null],
            ['payments', 'view', 'View Payments', null],
            ['payments', 'create', 'Process Payments', null],
            ['payments', 'update', 'Edit Payments', null],
            ['payments', 'verify', 'Verify Cash Payments', null],
            ['reports', 'view', 'View Reports', null],
            ['reports', 'export', 'Export Reports', null],
            ['settings', 'view', 'View Settings', null],
            ['settings', 'update', 'Update Settings', null],
            ['roles', 'view', 'View Roles', null],
            ['roles', 'create', 'Create Roles', null],
            ['roles', 'update', 'Edit Roles', null],
            ['roles', 'delete', 'Delete Roles', null],
            ['roles', 'assign', 'Assign Roles', null],
            ['users', 'view', 'View Users', null],
            ['users', 'create', 'Add Users', null],
            ['users', 'update', 'Edit Users', null],
            ['users', 'delete', 'Delete Users', null],
            ['users', 'reset-password', 'Reset User Passwords', null],
        ];

        foreach ($permissionCatalog as [$module, $action, $label, $hint]) {
            Permission::updateOrCreate(
                ['module' => $module, 'action' => $action],
                [
                    'code' => "{$module}.{$action}",
                    'label' => $label,
                    'hint' => $hint,
                ]
            );
        }

        $allCodes = Permission::pluck('code')->toArray();

        $systemRoles = [
            'admin' => ['description' => 'Full access to every module', 'permissions' => $allCodes],
            'accountant' => ['description' => 'Manage payments, fees and financial reports', 'permissions' => [
                'dashboard.view',
                'students.view',
                'payments.view', 'payments.create', 'payments.update', 'payments.verify',
                'fees.view', 'fees.create', 'fees.update', 'fees.delete',
                'reports.view', 'reports.export',
                'users.view',
            ]],
            'instructor' => ['description' => 'Record and manage exam results', 'permissions' => [
                'dashboard.view',
                'students.view',
                'courses.view',
                'instructors.view',
                'results.view', 'results.create', 'results.update', 'results.delete', 'results.publish',
                'reports.view',
            ]],
            'student' => ['description' => 'Student portal access', 'permissions' => []],
        ];

        foreach ($systemRoles as $name => $config) {
            $role = Role::updateOrCreate(
                ['name' => $name],
                [
                    'description' => $config['description'],
                    'is_system' => true,
                ]
            );

            $ids = Permission::whereIn('code', $config['permissions'])->pluck('id');
            $role->permissions()->sync($ids);
        }
    }
}