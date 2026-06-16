<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_only_sees_own_tasks(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        Task::factory()->for($owner)->create(['title' => 'Tugas A']);
        Task::factory()->for($other)->create(['title' => 'Tugas B']);

        $response = $this->actingAs($owner)->getJson('/tasks');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['title' => 'Tugas A']);
    }

    public function test_user_cannot_update_other_users_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->for($owner)->create();

        $this->actingAs($intruder)
            ->putJson("/tasks/{$task->id}", ['title' => 'Hack'])
            ->assertNotFound();
    }

    public function test_user_cannot_delete_other_users_task(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $task = Task::factory()->for($owner)->create();

        $this->actingAs($intruder)
            ->deleteJson("/tasks/{$task->id}")
            ->assertNotFound();
    }
}
