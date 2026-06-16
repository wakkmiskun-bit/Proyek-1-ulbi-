<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tasks = $request->user()
            ->tasks()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json($tasks);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['todo', 'doing', 'review', 'done'])],
            'priority' => ['required', Rule::in(['high', 'medium', 'low'])],
            'due_date' => ['nullable', 'date'],
            'checklist' => ['nullable', 'array'],
            'checklist.*.text' => ['required', 'string'],
            'checklist.*.done' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        // user_id otomatis dari akun yang sedang login — tidak bisa diisi dari form.
        $task = $request->user()->tasks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
            'checklist' => $validated['checklist'] ?? [],
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', Rule::in(['todo', 'doing', 'review', 'done'])],
            'priority' => ['sometimes', Rule::in(['high', 'medium', 'low'])],
            'due_date' => ['nullable', 'date'],
            'checklist' => ['nullable', 'array'],
            'checklist.*.text' => ['required', 'string'],
            'checklist.*.done' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $task->update($validated);

        return response()->json($task->fresh());
    }

    public function destroy(Request $request, Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json(['message' => 'Tugas berhasil dihapus.']);
    }
}
