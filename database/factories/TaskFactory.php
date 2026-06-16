<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(['todo', 'doing', 'review', 'done']),
            'priority' => fake()->randomElement(['high', 'medium', 'low']),
            'due_date' => fake()->optional()->date(),
            'checklist' => [],
            'sort_order' => 0,
        ];
    }
}
