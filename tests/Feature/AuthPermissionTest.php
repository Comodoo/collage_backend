<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_roles_endpoint(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $admin = User::where('email', 'admin@college.edu')->first();

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/roles');

        $response->assertStatus(200);
    }

    public function test_student_cannot_access_roles_endpoint(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $student = User::where('email', 'student@college.edu')->first();

        $response = $this->actingAs($student, 'sanctum')
            ->getJson('/api/roles');

        $response->assertStatus(403);
    }

    public function test_operations_user_has_inventory_permissions(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $ops = User::where('email', 'operations@college.edu')->first();

        $this->assertTrue($ops->canAction('inventory', 'view'));
        $this->assertTrue($ops->canAction('inventory', 'create'));
        $this->assertTrue($ops->canAction('inventory', 'delete'));
    }
}