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
        Route::bind('task', function (string $value) {
            $query = Task::query()->whereKey($value);

            if (auth('web')->check()) {
                $query->where('mahasiswa_id', auth('web')->id());
            }

            return $query->firstOrFail();
        });
    }
}
