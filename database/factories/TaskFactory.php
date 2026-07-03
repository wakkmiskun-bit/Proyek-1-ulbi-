<?php

namespace Database\Factories;

use App\Models\Mahasiswa;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'mahasiswa_id' => Mahasiswa::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(['todo', 'doing', 'review', 'done']),
            'priority' => fake()->randomElement(['high', 'medium', 'low']),
            'deadline' => fake()->optional()->date(),
            'checklist' => [],
            'sort_order' => 0,
        ];
    }
}
