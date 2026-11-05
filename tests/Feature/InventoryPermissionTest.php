<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_operations_can_list_inventory(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $ops = User::where('email', 'operations@college.edu')->first();

        $response = $this->actingAs($ops, 'sanctum')
            ->getJson('/api/inventory-items');

        $response->assertStatus(200);
    }

    public function test_student_cannot_list_inventory(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $student = User::where('email', 'student@college.edu')->first();

        $response = $this->actingAs($student, 'sanctum')
            ->getJson('/api/inventory-items');

        $response->assertStatus(403);
    }
}