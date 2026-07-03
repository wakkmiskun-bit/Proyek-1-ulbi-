<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tasks = $request->user('web')
            ->tasks()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json($tasks);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateTask($request);

        $payload = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'deadline' => $validated['deadline'] ?? null,
            'checklist' => $validated['checklist'] ?? [],
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        Task::applyAutoComplete($payload);

        $task = $request->user('web')->tasks()->create($payload);
        ActivityLogger::logTaskCreated($task);

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $previousStatus = $task->status;
        $validated = $this->validateTask($request, partial: true);
        Task::applyAutoComplete($validated);

        $task->update($validated);
        ActivityLogger::logTaskUpdated($task->fresh(), $previousStatus);

        return response()->json($task->fresh());
    }

    public function destroy(Request $request, Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        ActivityLogger::logTaskDeleted($task);
        $task->delete();

        return response()->json(['message' => 'Tugas berhasil dihapus.']);
    }

    public function getReminders(Request $request): JsonResponse
    {
        $today = now()->startOfDay();
        $baseQuery = fn () => $request->user('web')->tasks()
            ->whereIn('status', ['todo', 'doing', 'review'])
            ->whereNotNull('deadline');

        $overdueTasks = $baseQuery()
            ->where('deadline', '<', $today)
            ->get();

        $dueIn2Days = $baseQuery()
            ->whereDate('deadline', $today->copy()->addDays(2))
            ->get();

        $dueIn5Days = $baseQuery()
            ->whereDate('deadline', $today->copy()->addDays(5))
            ->get();

        $upcomingTasks = $baseQuery()
            ->whereBetween('deadline', [$today, $today->copy()->addDays(2)])
            ->get();

        return response()->json([
            'message' => 'Berhasil mengambil data pengingat tugas.',
            'summary' => [
                'total_overdue' => $overdueTasks->count(),
                'total_upcoming' => $upcomingTasks->count(),
                'total_h5' => $dueIn5Days->count(),
                'total_h2' => $dueIn2Days->count(),
            ],
            'reminders' => [
                'overdue' => $overdueTasks,
                'upcoming' => $upcomingTasks,
                'h5' => $dueIn5Days,
                'h2' => $dueIn2Days,
            ],
        ]);
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

        return $validated;
    }
}
