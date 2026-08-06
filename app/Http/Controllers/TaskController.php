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
            'mata_kuliah' => $validated['mata_kuliah'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'tag' => $validated['tag'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
            'checklist' => $validated['checklist'] ?? [],
            'attachments' => $validated['attachments'] ?? [],
            'assigned_to' => $validated['assigned_to'] ?? null,
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

        $reward = null;
        $freshTask = $task->fresh();

        // Gamification points award when marking task as done
        if ($previousStatus !== 'done' && $freshTask->status === 'done') {
            $user = $request->user('web');
            if ($user) {
                $pts = 10;
                $bonusMsg = '+10 Poin: Tugas selesai!';

                if ($freshTask->deadline) {
                    $diffDays = now()->startOfDay()->diffInDays($freshTask->deadline, false);
                    if ($diffDays >= 1) {
                        $pts = 15;
                        $bonusMsg = '+15 Poin Bonus! (Selesai H-1 sebelum deadline)';
                    }
                }

                $rewardResult = $user->addPoints($pts);
                $reward = array_merge($rewardResult, ['message' => $bonusMsg]);
            }
        }

        $resData = $freshTask->toArray();
        if ($reward) {
            $resData['reward'] = $reward;
        }

        return response()->json($resData);
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

    public function exportIcs(Request $request)
    {
        $tasks = $request->user('web')->tasks()->whereNotNull('deadline')->get();

        $ics = "BEGIN:VCALENDAR\r\n";
        $ics .= "VERSION:2.0\r\n";
        $ics .= "PRODID:-//TaskMate//NONSGML TaskMate Student Calendar//ID\r\n";
        $ics .= "CALSCALE:GREGORIAN\r\n";
        $ics .= "X-WR-CALNAME:Deadline TaskMate\r\n";

        foreach ($tasks as $t) {
            $dtStart = $t->deadline->format('Ymd\THis');
            $dtEnd = $t->deadline->copy()->addHour()->format('Ymd\THis');
            $summary = str_replace(["\r", "\n"], ' ', $t->title);
            $desc = str_replace(["\r", "\n"], ' ', ($t->mata_kuliah ? '['.$t->mata_kuliah.'] ' : '').($t->description ?? ''));

            $ics .= "BEGIN:VEVENT\r\n";
            $ics .= "UID:taskmate-".$t->id."@ulbi.ac.id\r\n";
            $ics .= "DTSTAMP:".now()->format('Ymd\THis\Z')."\r\n";
            $ics .= "DTSTART;VALUE=DATE:".$t->deadline->format('Ymd')."\r\n";
            $ics .= "SUMMARY:".$summary."\r\n";
            $ics .= "DESCRIPTION:".$desc."\r\n";
            $ics .= "STATUS:".($t->status === 'done' ? 'CANCELLED' : 'CONFIRMED')."\r\n";
            $ics .= "END:VEVENT\r\n";
        }

        $ics .= "END:VCALENDAR\r\n";

        return response($ics, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="taskmate_calendar.ics"',
        ]);
    }

    private function validateTask(Request $request, bool $partial = false): array
    {
        $rules = [
            'title' => [$partial ? 'sometimes' : 'required', 'string', 'max:255'],
            'mata_kuliah' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => [$partial ? 'sometimes' : 'required', Rule::in(['todo', 'doing', 'review', 'done'])],
            'priority' => [$partial ? 'sometimes' : 'required', Rule::in(['high', 'medium', 'low'])],
            'tag' => ['nullable', 'string', 'max:100'],
            'deadline' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'checklist' => ['nullable', 'array'],
            'checklist.*.text' => ['required', 'string'],
            'checklist.*.done' => ['boolean'],
            'attachments' => ['nullable', 'array'],
            'assigned_to' => ['nullable', 'string', 'max:255'],
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
