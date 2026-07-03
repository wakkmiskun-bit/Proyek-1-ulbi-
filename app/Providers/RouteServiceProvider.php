<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Mahasiswa;
use App\Models\Task;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard';

    public function boot(): void
    {
        Route::bind('task', function (string $value) {
            if (Auth::guard('admin')->check()) {
                return Task::query()->findOrFail($value);
            }

            $mahasiswa = Auth::guard('web')->user();

            if (! $mahasiswa) {
                abort(404);
            }

            return $mahasiswa->tasks()->whereKey($value)->firstOrFail();
        });

        Route::bind('mahasiswa', fn (string $value) => Mahasiswa::query()->findOrFail($value));

        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            $id = $request->user('web')?->id ?: $request->ip();

            return Limit::perMinute(60)->by($id);
        });
    }
}
