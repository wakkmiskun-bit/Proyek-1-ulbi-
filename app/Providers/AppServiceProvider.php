<?php

namespace App\Providers;

use App\Models\Task;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Setiap tugas hanya bisa diakses oleh pemilik akun (user_id dari session).
        Route::bind('task', function (string $value) {
            return Task::query()
                ->where('user_id', auth()->id())
                ->findOrFail($value);
        });
    }
}
