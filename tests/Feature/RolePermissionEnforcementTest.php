<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionEnforcementTest extends TestCase
{
    use RefreshDatabase;

    public function test_custom_role_permissions_are_enforced(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        // Create a custom role with only inventory.view
        $role = Role::create([
            'id' => 'CUSTOM_INV',
            'name' => 'Custom Inventory Viewer',
            'description' => 'Test role',
            'is_system' => false,
        ]);

        $permission = Permission::where('code', 'inventory.view')->firstOrFail();
        $role->permissions()->attach($permission->id);

        // Create an operations portal user
        $user = User::create([
            'first_name' => 'Test',
            'last_name' => 'Ops',
            'email' => 'testops@college.edu',
            'password' => bcrypt('password123'),
            'role' => 'operations',
            'phone' => '0700000000',
        ]);

        // Assign the custom role
        $user->roles()->attach($role->id);

        // Should access inventory
        $this->actingAs($user, 'sanctum')
            ->getJson('/api/inventory-items')
            ->assertStatus(200);

        // Should NOT access HR
        $this->actingAs($user, 'sanctum')
            ->getJson('/api/hr-profiles')
            ->assertStatus(403);

        // Should NOT access budgets
        $this->actingAs($user, 'sanctum')
            ->getJson('/api/budgets')
            ->assertStatus(403);
    }

    public function test_role_permission_update_invalidates_cache(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $role = Role::where('id', 'OPERATIONS_PLANNER')->firstOrFail();
        $user = User::where('email', 'operations@college.edu')->firstOrFail();

        // Ensure user has the role
        $user->roles()->syncWithoutDetaching($role->id);

        // User can access inventory initially
        $this->actingAs($user, 'sanctum')
            ->getJson('/api/inventory-items')
            ->assertStatus(200);

        // Remove inventory.view from the role
        $role->permissions()->detach(
            Permission::where('code', 'inventory.view')->firstOrFail()->id
        );

        // Invalidate cache
        app(\App\Services\PermissionService::class)->forget($user);

        // Now should be denied
        $this->actingAs($user, 'sanctum')
            ->getJson('/api/inventory-items')
            ->assertStatus(403);
    }
}