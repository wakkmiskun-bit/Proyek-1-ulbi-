<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_task(): void
    {
        $user = Mahasiswa::factory()->create();

        $response = $this->actingAs($user, 'web')->postJson('/tasks', [
            'title' => 'Belajar Matematika',
            'status' => 'todo',
            'priority' => 'medium',
            'due_date' => '2026-07-17',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'Belajar Matematika']);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Belajar Matematika',
            'status' => 'todo',
            'priority' => 'medium',
            'deadline' => '2026-07-17',
        ]);
    }
}
