<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Mahasiswa;
use App\Models\Task;
use App\Services\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'stats' => $this->buildStats(),
            'mahasiswas' => $this->mahasiswaListingQuery()->get(),
        ]);
    }

    public function userBoard(Mahasiswa $mahasiswa): View
    {
        return view('admin.user-board', compact('mahasiswa'));
    }

    public function stats(): JsonResponse
    {
        return response()->json($this->buildStats());
    }

    public function mahasiswas(): JsonResponse
    {
        return response()->json(
            $this->mahasiswaListingQuery()->get(['id', 'nim', 'nama', 'email', 'phone', 'foto', 'universitas', 'created_at'])
        );
    }

    public function show(Request $request, Mahasiswa $mahasiswa): View|JsonResponse
    {
        $mahasiswa->load(['tasks' => fn ($q) => $q->orderBy('sort_order')->orderBy('id')]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($mahasiswa);
        }

        $activities = Activity::query()
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return view('admin.show', compact('mahasiswa', 'activities'));
    }

    public function createMahasiswa(): View
    {
        return view('admin.create');
    }

    public function storeMahasiswa(Request $request): JsonResponse
    {
        if ($request->has('name') && ! $request->has('nama')) {
            $request->merge(['nama' => $request->input('name')]);
        }

        $validated = $request->validate([
            'nim' => ['required', 'string', 'max:20', Rule::unique('mahasiswas', 'nim')],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('mahasiswas', 'email')],
            'phone' => ['nullable', 'string', 'max:20'],
            'universitas' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'integer', 'min:1', 'max:8'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if (array_key_exists('phone', $validated) && $validated['phone']) {
            $validated['phone'] = Mahasiswa::normalizePhone($validated['phone']);
        }

        $mahasiswa = Mahasiswa::create($validated);
        ActivityLogger::logMahasiswaRegistered($mahasiswa);

        return response()->json($mahasiswa, 201);
    }

    public function edit(Mahasiswa $mahasiswa): View
    {
        return view('admin.edit', compact('mahasiswa'));
    }

    public function editMahasiswa(Mahasiswa $mahasiswa): RedirectResponse
    {
        return redirect()->route('admin.mahasiswas.edit', $mahasiswa->id);
    }

    public function activities(): JsonResponse
    {
        $activities = Activity::query()
            ->with('mahasiswa:id,nim,nama,foto')
            ->orderByDesc('created_at')
            ->limit(40)
            ->get()
            ->map(fn (Activity $activity) => [
                'id' => $activity->id,
                'activity_text' => $activity->activity_text,
                'status' => $activity->status_tugas,
                'time' => $activity->created_at->diffForHumans(),
                'created_at' => $activity->created_at->toIso8601String(),
                'user' => [
                    'id' => $activity->mahasiswa->id,
                    'nim' => $activity->mahasiswa->nim,
                    'name' => $activity->mahasiswa->nama,
                    'photo_url' => $activity->mahasiswa->photo_url,
                ],
            ]);

        return response()->json($activities);
    }

    public function showMahasiswa(Request $request, Mahasiswa $mahasiswa): JsonResponse
    {
        // Hanya untuk AJAX API, tidak untuk browser redirect
        $mahasiswa->load(['tasks' => fn ($q) => $q->orderBy('sort_order')->orderBy('id')]);

        return response()->json($mahasiswa);
    }

    public function updateMahasiswa(Request $request, Mahasiswa $mahasiswa): JsonResponse
    {
        if ($request->has('name') && ! $request->has('nama')) {
            $request->merge(['nama' => $request->input('name')]);
        }

        $validated = $request->validate([
            'nim' => ['sometimes', 'required', 'string', 'max:20', Rule::unique('mahasiswas', 'nim')->ignore($mahasiswa->id)],
            'nama' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('mahasiswas', 'email')->ignore($mahasiswa->id)],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],
            'universitas' => ['sometimes', 'required', 'string', 'max:255'],
            'semester' => ['sometimes', 'required', 'integer', 'min:1', 'max:8'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (! empty($validated['password'])) {
            // Di-hash otomatis oleh cast "hashed" pada model Mahasiswa.
        } else {
            unset($validated['password']);
        }

        if (array_key_exists('phone', $validated) && $validated['phone']) {
            $validated['phone'] = Mahasiswa::normalizePhone($validated['phone']);
        }

        $mahasiswa->update($validated);
        ActivityLogger::logMahasiswaUpdated($mahasiswa->fresh());

        return response()->json($mahasiswa->fresh());
    }

    public function destroyMahasiswa(Mahasiswa $mahasiswa): JsonResponse
    {
        $mahasiswa->delete();

        return response()->json(['message' => 'Akun mahasiswa berhasil dihapus beserta semua datanya.']);
    }

    public function storeTask(Request $request, Mahasiswa $mahasiswa): JsonResponse
    {
        $validated = $this->validateTask($request);
        Task::applyAutoComplete($validated);

        $task = $mahasiswa->tasks()->create($validated);
        ActivityLogger::logTaskCreated($task);

        return response()->json($task, 201);
    }

    public function updateTask(Request $request, Task $task): JsonResponse
    {
        $previousStatus = $task->status;
        $validated = $this->validateTask($request, partial: true);
        Task::applyAutoComplete($validated);

        $task->update($validated);
        ActivityLogger::logTaskUpdated($task->fresh(), $previousStatus);

        return response()->json($task->fresh());
    }

    public function destroyTask(Task $task): JsonResponse
    {
        ActivityLogger::logTaskDeleted($task);
        $task->delete();

        return response()->json(['message' => 'Tugas berhasil dihapus.']);
    }

    private function buildStats(): array
    {
        return [
            'mahasiswa' => Mahasiswa::query()->count(),
            'tasks' => Task::query()->count(),
            'done' => Task::query()->where('status', 'done')->count(),
        ];
    }

    private function mahasiswaListingQuery()
    {
        return Mahasiswa::query()
            ->withCount([
                'tasks',
                'tasks as todo_count' => fn ($q) => $q->where('status', 'todo'),
                'tasks as doing_count' => fn ($q) => $q->where('status', 'doing'),
                'tasks as review_count' => fn ($q) => $q->where('status', 'review'),
                'tasks as done_count' => fn ($q) => $q->where('status', 'done'),
            ])
            ->orderBy('nama');
    }

    private function validateTask(Request $request, bool $partial = false): array
    {
        $rules = [
            'title' => [$partial ? 'sometimes' : 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => [$partial ? 'sometimes' : 'required', Rule::in(['todo', 'doing', 'review', 'done'])],
            'priority' => [$partial ? 'sometimes' : 'required', Rule::in(['high', 'medium', 'low'])],
            'deadline' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'checklist' => ['nullable', 'array'],
            'checklist.*.text' => ['required', 'string'],
            'checklist.*.done' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];

        $validated = $request->validate($rules);

        if (isset($validated['due_date']) && ! isset($validated['deadline'])) {
            $validated['deadline'] = $validated['due_date'];
        }

        unset($validated['due_date']);

        if (array_key_exists('checklist', $validated) && $validated['checklist'] === null) {
            $validated['checklist'] = [];
        }

        return $validated;
    }

    public function adminsIndex(): JsonResponse
    {
        return response()->json(
            \App\Models\Admin::query()->orderBy('nama')->get(['id', 'nama', 'email', 'created_at'])
        );
    }

    public function adminsStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $admin = \App\Models\Admin::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        return response()->json($admin, 201);
    }

    public function adminsUpdate(Request $request, \App\Models\Admin $admin): JsonResponse
    {
        $validated = $request->validate([
            'nama' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('admins')->ignore($admin->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $admin->update($validated);

        return response()->json($admin);
    }

    public function adminsDestroy(\App\Models\Admin $admin): JsonResponse
    {
        if ($admin->id === auth('admin')->id()) {
            return response()->json(['message' => 'Anda tidak bisa menghapus akun Anda sendiri.'], 403);
        }

        $admin->delete();

        return response()->json(['message' => 'Akun admin berhasil dihapus.']);
    }
}
