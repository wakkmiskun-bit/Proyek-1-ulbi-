<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Mahasiswa;
use App\Models\Task;

class ActivityLogger
{
    public static function logTaskCreated(Task $task): void
    {
        self::write(
            $task->mahasiswa_id,
            "Menambahkan tugas \"{$task->title}\"",
            $task->status
        );
    }

    public static function logTaskUpdated(Task $task, ?string $previousStatus = null): void
    {
        $message = $previousStatus && $previousStatus !== $task->status
            ? "Memindahkan tugas \"{$task->title}\" ke ".strtoupper($task->status)
            : "Memperbarui tugas \"{$task->title}\"";

        self::write($task->mahasiswa_id, $message, $task->status);
    }

    public static function logTaskDeleted(Task $task): void
    {
        self::write(
            $task->mahasiswa_id,
            "Menghapus tugas \"{$task->title}\"",
            $task->status
        );
    }

    public static function logMahasiswaUpdated(Mahasiswa $mahasiswa): void
    {
        self::write(
            $mahasiswa->id,
            'Admin memperbarui profil mahasiswa',
            'todo'
        );
    }

    public static function logMahasiswaRegistered(Mahasiswa $mahasiswa): void
    {
        self::write(
            $mahasiswa->id,
            "Mahasiswa baru terdaftar: {$mahasiswa->nama} ({$mahasiswa->nim})",
            'todo'
        );
    }

    private static function write(int $mahasiswaId, string $text, string $status): void
    {
        Activity::create([
            'mahasiswa_id' => $mahasiswaId,
            'activity_text' => $text,
            'status_tugas' => $status,
            'created_at' => now(),
        ]);
    }
}
