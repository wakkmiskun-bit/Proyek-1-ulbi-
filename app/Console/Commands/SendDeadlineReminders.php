<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\TaskReminder;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendDeadlineReminders extends Command
{
    protected $signature = 'tasks:send-deadline-reminders';

    protected $description = 'Kirim pengingat WhatsApp H-5 dan H-2 sebelum deadline tugas';

    public function handle(WhatsAppService $whatsapp): int
    {
        $daysList = [5, 2];
        $sent = 0;

        foreach ($daysList as $daysBefore) {
            $targetDate = Carbon::today()->addDays($daysBefore);

            $tasks = Task::query()
                ->with('mahasiswa:id,nama,phone')
                ->whereDate('deadline', $targetDate)
                ->whereIn('status', ['todo', 'doing', 'review'])
                ->whereHas('mahasiswa', fn ($q) => $q->whereNotNull('phone'))
                ->get();

            foreach ($tasks as $task) {
                if (TaskReminder::query()->where('task_id', $task->id)->where('days_before', $daysBefore)->exists()) {
                    continue;
                }

                $phone = $task->mahasiswa->phone;
                if (! $phone) {
                    continue;
                }

                $due = $task->deadline?->format('d M Y') ?? '-';
                $message = "Halo {$task->mahasiswa->nama}! ⏰\n\n"
                    ."Pengingat TaskMate: tugas \"{$task->title}\" akan deadline dalam {$daysBefore} hari ({$due}).\n"
                    ."Yuk segera dikerjakan agar tidak telat! 💪";

                if ($whatsapp->send($phone, $message)) {
                    TaskReminder::create([
                        'task_id' => $task->id,
                        'mahasiswa_id' => $task->mahasiswa_id,
                        'days_before' => $daysBefore,
                        'sent_at' => now(),
                    ]);
                    $sent++;
                }
            }
        }

        $this->info("Pengingat WhatsApp terkirim: {$sent}");

        return self::SUCCESS;
    }
}
