<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::query()->updateOrCreate(
            ['email' => 'admin1@gmail.com'],
            [
                'nama' => 'Administrator TaskMate',
                'password' => 'admin111',
            ]
        );
    }
}
