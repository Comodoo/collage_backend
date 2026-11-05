<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeds the permission catalog for the admin panel and the built-in roles.
 *
 * The permission catalog is derived from config/admin_permissions.php so the
 * admin panel "pages" (modules) and their actions have a single source of
 * truth. Role matrices below define the built-in roles' default grants.
 */
class RolePermissionSeeder extends Seeder
{
    /**
     * Built-in role => [module => [actions...]].
     *
     * ADMIN is granted every action from the manifest programmatically.
     */
    private const ROLE_MATRICES = [
        'HOD' => [
            'dashboard'     => ['view'],
            'students'      => ['view', 'update'],
            'staff'         => ['view'],
            'registrations' => ['view', 'approve', 'reject'],
            'results'       => ['view', 'publish', 'reject'],
            'reports'       => ['view'],
        ],
        'EXAM_OFFICER' => [
            'dashboard' => ['view'],
            'courses'   => ['view'],
            'results'   => ['view', 'create', 'update', 'delete', 'publish', 'reject'],
            'reports'   => ['view', 'export'],
        ],
        'ACCOUNTANT' => [
            'dashboard'     => ['view'],
            'registrations' => ['view'],
            'payments'      => ['view', 'verify'],
            'reports'       => ['view'],
        ],
        'OPERATIONS_PLANNER' => [
            'inventory' => ['view', 'create', 'update', 'delete', 'receive', 'assign', 'transfer', 'return', 'dispose', 'audit', 'export'],
            'hr' => ['view', 'create', 'update', 'delete', 'documents.manage', 'leave.manage', 'status.manage', 'export'],
            'salary_scales' => ['view', 'create', 'update', 'delete', 'assign', 'export'],
            'budgets' => ['view', 'create', 'update', 'submit', 'review', 'approve', 'reject', 'close', 'export'],
            'proposals' => ['view', 'create', 'update', 'submit', 'review', 'approve', 'reject', 'publish', 'export'],
            'reports' => ['operations.view', 'financial.view', 'export'],
        ],
    ];

    private const ROLE_META = [
        'ADMIN' => [
            'name' => 'Administrator',
            'description' => 'Full access to every admin panel page and action.',
        ],
        'HOD' => [
            'name' => 'Head of Department',
            'description' => 'Department head with limited admin panel access.',
        ],
        'EXAM_OFFICER' => [
            'name' => 'EXAM OFFICER',
            'description' => 'Manages exam results within the admin panel.',
        ],
        'ACCOUNTANT' => [
            'name' => 'Accountant',
            'description' => 'Finance portal access to registrations and payments.',
        ],
        'OPERATIONS_PLANNER' => [
            'name' => 'Operations & Planning Officer',
            'description' => 'Operations and planning portal access.',
            'is_system' => true,
        ],
    ];

    public function run(): void
    {
        $modules = config('admin_permissions.modules', []);

        $permissionIds = $this->seedPermissionCatalog($modules);

        // ADMIN gets every permission declared in the manifest.
        $matrices = ['ADMIN' => $this->allActions($modules)] + self::ROLE_MATRICES;

        foreach ($matrices as $roleId => $roleModules) {
            $role = Role::firstOrCreate(
                ['id' => $roleId],
                [
                    'name' => self::ROLE_META[$roleId]['name'] ?? ucwords(str_replace('_', ' ', $roleId)),
                    'description' => self::ROLE_META[$roleId]['description'] ?? null,
                    'is_system' => true,
                ]
            );

            $codes = [];
            foreach ($roleModules as $module => $actions) {
                foreach ($actions as $action) {
                    $codes[] = "{$module}.{$action}";
                }
            }

            $role->permissions()->sync(
                collect($codes)
                    ->map(fn (string $code) => $permissionIds[$code] ?? null)
                    ->filter()
                    ->values()
            );
        }

        $this->attachRoleToPortalUsers('ADMIN', 'admin');
        $this->attachRoleToPortalUsers('ACCOUNTANT', 'accountant');
        $this->attachRoleToPortalUsers('OPERATIONS_PLANNER', 'operations');

        $this->command->info('Permissions catalog and roles seeded.');
    }

    /**
     * Every action declared in the manifest, as [module => [actions]].
     *
     * @param  array<string, array{label: string, actions: array<string, string>}>  $modules
     * @return array<string, array<int, string>>
     */
    private function allActions(array $modules): array
    {
        $all = [];
        foreach ($modules as $module => $definition) {
            $all[$module] = array_keys($definition['actions'] ?? []);
        }

        return $all;
    }

    /**
     * @param  array<string, array{label: string, actions: array<string, string>}>  $modules
     * @return array<string, int> map of code => permission id
     */
    private function seedPermissionCatalog(array $modules): array
    {
        $ids = [];
        foreach ($modules as $module => $definition) {
            $moduleLabel = $definition['label'] ?? ucwords(str_replace('_', ' ', $module));

            foreach ($definition['actions'] as $action => $label) {
                $code = "{$module}.{$action}";

                $permission = Permission::updateOrCreate(
                    ['code' => $code],
                    [
                        'module' => $module,
                        'action' => $action,
                        'label' => $label,
                        'hint' => "{$moduleLabel} — {$label}",
                    ]
                );

                $ids[$code] = $permission->id;
            }
        }

        return $ids;
    }

    /**
     * Attach a role to every user whose portal role matches, so built-in
     * portal users show their effective roles without manual assignment.
     */
    private function attachRoleToPortalUsers(string $roleId, string $portalRole): void
    {
        $role = Role::find($roleId);
        if (!$role) {
            return;
        }

        User::where('role', $portalRole)->get()->each(function (User $user) use ($role) {
            if (!$user->roles()->where('roles.id', $role->id)->exists()) {
                $user->roles()->attach($role->id);
            }
        });
    }
}
