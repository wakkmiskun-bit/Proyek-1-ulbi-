<?php

namespace App\Policies;

use App\Models\Mahasiswa;
use App\Models\Task;

class TaskPolicy
{
    public function view(Mahasiswa $mahasiswa, Task $task): bool
    {
        return $mahasiswa->id === $task->mahasiswa_id;
    }

    public function update(Mahasiswa $mahasiswa, Task $task): bool
    {
        return $mahasiswa->id === $task->mahasiswa_id;
    }

    public function delete(Mahasiswa $mahasiswa, Task $task): bool
    {
        return $mahasiswa->id === $task->mahasiswa_id;
    }
}
