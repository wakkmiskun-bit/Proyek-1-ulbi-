<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_only_sees_own_tasks(): void
    {
        $owner = Mahasiswa::factory()->create();
        $other = Mahasiswa::factory()->create();

        Task::factory()->for($owner, 'mahasiswa')->create(['title' => 'Tugas A']);
        Task::factory()->for($other, 'mahasiswa')->create(['title' => 'Tugas B']);

        $response = $this->actingAs($owner, 'web')->getJson('/tasks');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['title' => 'Tugas A']);
    }

    public function test_user_cannot_update_other_users_task(): void
    {
        $owner = Mahasiswa::factory()->create();
        $intruder = Mahasiswa::factory()->create();
        $task = Task::factory()->for($owner, 'mahasiswa')->create();

        $this->actingAs($intruder, 'web')
            ->putJson("/tasks/{$task->id}", ['title' => 'Hack'])
            ->assertNotFound();
    }

    public function test_user_cannot_delete_other_users_task(): void
    {
        $owner = Mahasiswa::factory()->create();
        $intruder = Mahasiswa::factory()->create();
        $task = Task::factory()->for($owner, 'mahasiswa')->create();

        $this->actingAs($intruder, 'web')
            ->deleteJson("/tasks/{$task->id}")
            ->assertNotFound();
    }
}
