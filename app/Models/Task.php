<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'title',
        'mata_kuliah',
        'description',
        'status',
        'deadline',
        'priority',
        'tag',
        'checklist',
        'attachments',
        'assigned_to',
        'sort_order',
    ];

    protected $casts = [
        'deadline' => 'date',
        'checklist' => 'array',
        'attachments' => 'array',
    ];

    protected $appends = [
        'due_date',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function taskReminders(): HasMany
    {
        return $this->hasMany(TaskReminder::class);
    }

    /** Kompatibilitas API Kanban lama (due_date = deadline). */
    public function getDueDateAttribute(): ?string
    {
        return $this->deadline?->format('Y-m-d');
    }

    public static function applyAutoComplete(array &$data): void
    {
        $checklist = $data['checklist'] ?? null;

        if (! is_array($checklist) || count($checklist) === 0) {
            return;
        }

        $allDone = collect($checklist)->every(fn ($item) => ! empty($item['done']));

        if ($allDone) {
            $data['status'] = 'done';
        }
    }
}
