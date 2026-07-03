<?php

namespace Database\Factories;

use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    protected $model = Mahasiswa::class;

    public function definition(): array
    {
        return [
            'nim' => fake()->unique()->numerify('##########'),
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '628'.fake()->numerify('##########'),
            'universitas' => fake()->randomElement(['Universitas Logistik dan Bisnis Internasional', 'Universitas Indonesia', 'Institut Teknologi Bandung']),
            'password' => 'password',
        ];
    }
}
