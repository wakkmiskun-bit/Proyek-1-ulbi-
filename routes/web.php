<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TaskController;
use App\Models\Mahasiswa;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }

    if (Auth::guard('web')->check()) {
        return redirect()->route('dashboard');
    }

    return view('home', [
        'mahasiswaCount' => Mahasiswa::query()->count(),
        'taskCount' => Task::query()->count(),
        'doneCount' => Task::query()->where('status', 'done')->count(),
    ]);
});

Route::get('/dashboard', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }

    $mahasiswa = Auth::guard('web')->user();
    $tasks = $mahasiswa->tasks()->orderBy('sort_order')->get();
    
    $totalTasks = $tasks->count();
    $doneCount = $tasks->where('status', 'done')->count();
    $activeCount = $totalTasks - $doneCount;
    $completionRate = $totalTasks > 0 ? round(($doneCount / $totalTasks) * 100) : 0;

    $upcomingTasks = $mahasiswa->tasks()
        ->where('status', '!=', 'done')
        ->whereNotNull('deadline')
        ->orderBy('deadline')
        ->take(5)
        ->get();

    $activities = $mahasiswa->activities()->latest()->take(5)->get();

    // Hitung progres per mata kuliah (task yang punya mata_kuliah)
    $subjectProgress = $mahasiswa->tasks()
        ->whereNotNull('mata_kuliah')
        ->get()
        ->groupBy('mata_kuliah')
        ->map(function ($tasks) {
            $total = $tasks->count();
            $done  = $tasks->where('status', 'done')->count();
            return [
                'nama'       => $tasks->first()->mata_kuliah,
                'total'      => $total,
                'done'       => $done,
                'percentage' => $total > 0 ? round(($done / $total) * 100) : 0,
            ];
        })
        ->values();

    return view('dashboard', [
        'tasks'           => $tasks,
        'totalTasks'      => $totalTasks,
        'doneCount'       => $doneCount,
        'activeCount'     => $activeCount,
        'completionRate'  => $completionRate,
        'upcomingTasks'   => $upcomingTasks,
        'activities'      => $activities,
        'subjectProgress' => $subjectProgress,
    ]);
})->middleware(['auth:web'])->name('dashboard');

Route::middleware('auth:web')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    Route::get('/tasks-reminders', [TaskController::class, 'getReminders']);
    Route::get('/tasks/export-ics', [TaskController::class, 'exportIcs'])->name('tasks.export-ics');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth:admin', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/stats', [AdminDashboardController::class, 'stats'])->name('stats');
    Route::get('/admins', [AdminDashboardController::class, 'adminsIndex'])->name('admins.index');
    Route::post('/admins', [AdminDashboardController::class, 'adminsStore'])->name('admins.store');
    Route::put('/admins/{admin}', [AdminDashboardController::class, 'adminsUpdate'])->name('admins.update');
    Route::delete('/admins/{admin}', [AdminDashboardController::class, 'adminsDestroy'])->name('admins.destroy');
    Route::get('/mahasiswas', [AdminDashboardController::class, 'mahasiswas'])->name('mahasiswas.index');
    Route::get('/activities', [AdminDashboardController::class, 'activities'])->name('activities');
    Route::get('/mahasiswas/create', [AdminDashboardController::class, 'createMahasiswa'])->name('mahasiswas.create');
    Route::post('/mahasiswas', [AdminDashboardController::class, 'storeMahasiswa'])->name('mahasiswas.store');
    Route::get('/mahasiswas/{mahasiswa}/board', [AdminDashboardController::class, 'userBoard'])->name('mahasiswas.board');
    Route::get('/mahasiswas/{mahasiswa}', [AdminDashboardController::class, 'show'])->name('mahasiswas.show');
    Route::get('/mahasiswas/{mahasiswa}/edit', [AdminDashboardController::class, 'edit'])->name('mahasiswas.edit');
    Route::put('/mahasiswas/{mahasiswa}', [AdminDashboardController::class, 'updateMahasiswa'])->name('mahasiswas.update');
    Route::delete('/mahasiswas/{mahasiswa}', [AdminDashboardController::class, 'destroyMahasiswa'])->name('mahasiswas.destroy');
    Route::post('/mahasiswas/{mahasiswa}/tasks', [AdminDashboardController::class, 'storeTask'])->name('mahasiswas.tasks.store');
    Route::put('/tasks/{task}', [AdminDashboardController::class, 'updateTask'])->name('tasks.update');
    Route::delete('/tasks/{task}', [AdminDashboardController::class, 'destroyTask'])->name('tasks.destroy');
    // Bantuan / Support Tickets
    Route::get('/bantuan', [SupportController::class, 'adminIndex'])->name('support.index');
    Route::patch('/bantuan/{ticket}/status', [SupportController::class, 'adminUpdateStatus'])->name('support.status');
    Route::delete('/bantuan/{ticket}', [SupportController::class, 'adminDestroy'])->name('support.destroy');
});

Route::get('/welcome', function () {
    return view('welcome');
})->middleware(['auth:web']);

Route::get('/bantuan', [SupportController::class, 'create'])->name('bantuan');
Route::post('/bantuan', [SupportController::class, 'store'])->name('bantuan.store');

require __DIR__.'/auth.php';

