<?php

/*
|--------------------------------------------------------------------------
| Admin Panel Permission Manifest
|--------------------------------------------------------------------------
|
| Single source of truth for every admin panel "page" (module) and the
| actions that can be granted on it. The RolePermissionSeeder turns this
| into rows in the `permissions` table, which in turn drives the
| Roles & Permissions matrix in the frontend.
|
| When you add a new admin page, add its module + actions here and the
| permission matrix will pick it up on the next `db:seed`.
|
| module => [
|     'label'   => human readable page name,
|     'actions' => [action => human readable label],
| ]
|
*/

return [
    'modules' => [
        'dashboard' => [
            'label' => 'Dashboard',
            'actions' => [
                'view' => 'View dashboard',
            ],
        ],
        'students' => [
            'label' => 'Students',
            'actions' => [
                'view' => 'View students',
                'create' => 'Add student',
                'update' => 'Edit student',
                'delete' => 'Delete student',
                'reset_password' => 'Reset student password',
            ],
        ],
        'staff' => [
            'label' => 'Staff',
            'actions' => [
                'view' => 'View staff',
                'create' => 'Add staff',
                'update' => 'Edit staff',
                'delete' => 'Delete staff',
            ],
        ],
        'programs' => [
            'label' => 'Programs & Departments',
            'actions' => [
                'view' => 'View programs',
                'create' => 'Add program/department',
                'update' => 'Edit program/department',
                'delete' => 'Delete program/department',
            ],
        ],
        'courses' => [
            'label' => 'Courses',
            'actions' => [
                'view' => 'View courses',
                'create' => 'Add course',
                'update' => 'Edit course',
                'delete' => 'Delete course',
                'assign_instructor' => 'Assign instructors',
            ],
        ],
        'registrations' => [
            'label' => 'Registrations',
            'actions' => [
                'view' => 'View registrations',
                'create' => 'Create registration',
                'update' => 'Edit registration',
                'delete' => 'Delete registration',
                'approve' => 'Approve registration',
                'reject' => 'Reject registration',
            ],
        ],
        'results' => [
            'label' => 'Exam Results',
            'actions' => [
                'view' => 'View results',
                'create' => 'Create result',
                'update' => 'Edit result',
                'delete' => 'Delete result',
                'publish' => 'Publish results',
                'reject' => 'Reject results',
            ],
        ],
        'payments' => [
            'label' => 'Payments',
            'actions' => [
                'view' => 'View payments',
                'create' => 'Record payment',
                'verify' => 'Verify cash payment',
            ],
        ],
        'reports' => [
            'label' => 'Reports',
            'actions' => [
                'view' => 'View reports',
                'export' => 'Export reports',
            ],
        ],
        'settings' => [
            'label' => 'Settings (Fees & Credits)',
            'actions' => [
                'view' => 'View settings',
                'update' => 'Update settings',
            ],
        ],
        'roles' => [
            'label' => 'Roles & Permissions',
            'actions' => [
                'view' => 'View roles',
                'create' => 'Create role',
                'update' => 'Update role permissions',
                'delete' => 'Delete role',
                'assign' => 'Assign roles to users',
            ],
        ],
        'inventory' => [
            'label' => 'Inventory',
            'actions' => [
                'view' => 'View inventory',
                'create' => 'Create inventory item',
                'update' => 'Update inventory item',
                'delete' => 'Delete inventory item',
                'receive' => 'Receive inventory',
                'assign' => 'Assign inventory',
                'transfer' => 'Transfer inventory',
                'return' => 'Return inventory',
                'dispose' => 'Dispose inventory',
                'audit' => 'Audit inventory',
                'export' => 'Export inventory',
            ],
        ],
        'hr' => [
            'label' => 'Human Resources',
            'actions' => [
                'view' => 'View HR records',
                'create' => 'Create HR record',
                'update' => 'Update HR record',
                'delete' => 'Delete HR record',
                'documents.manage' => 'Manage HR documents',
                'leave.manage' => 'Manage leave',
                'status.manage' => 'Manage employment status',
                'export' => 'Export HR data',
            ],
        ],
        'salary_scales' => [
            'label' => 'Salary Scales',
            'actions' => [
                'view' => 'View salary scales',
                'create' => 'Create salary scale',
                'update' => 'Update salary scale',
                'delete' => 'Delete salary scale',
                'assign' => 'Assign salary scale',
                'export' => 'Export salary scales',
            ],
        ],
        'budgets' => [
            'label' => 'Budgets',
            'actions' => [
                'view' => 'View budgets',
                'create' => 'Create budget',
                'update' => 'Update budget',
                'submit' => 'Submit budget',
                'review' => 'Review budget',
                'approve' => 'Approve budget',
                'reject' => 'Reject budget',
                'close' => 'Close budget',
                'export' => 'Export budgets',
            ],
        ],
        'proposals' => [
            'label' => 'Proposals & Plans',
            'actions' => [
                'view' => 'View proposals',
                'create' => 'Create proposal',
                'update' => 'Update proposal',
                'submit' => 'Submit proposal',
                'review' => 'Review proposal',
                'approve' => 'Approve proposal',
                'reject' => 'Reject proposal',
                'publish' => 'Publish proposal',
                'export' => 'Export proposals',
            ],
        ],
        'reports' => [
            'label' => 'Reports',
            'actions' => [
                'view' => 'View reports',
                'export' => 'Export reports',
                'operations.view' => 'View operations reports',
                'financial.view' => 'View financial reports',
            ],
        ],
    ],
];
