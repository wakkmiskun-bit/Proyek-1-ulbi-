<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Mahasiswa extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'mahasiswas';

    protected $fillable = [
        'nim',
        'nama',
        'email',
        'password',
        'phone',
        'foto',
        'universitas',
        'semester',
        'points',
        'level',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'points' => 'integer',
        'level' => 'integer',
    ];

    protected $appends = [
        'name',
        'photo_url',
        'level_title',
        'next_level_points',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function taskReminders(): HasMany
    {
        return $this->hasMany(TaskReminder::class);
    }

    public function getNameAttribute(): string
    {
        return $this->nama;
    }

    public function getPhotoAttribute(): ?string
    {
        return $this->foto;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->foto ? asset('storage/'.$this->foto) : null;
    }

    public function getLevelTitleAttribute(): string
    {
        $pts = $this->points ?? 0;
        if ($pts >= 350) return 'Level 5: Legenda ULBI 👑';
        if ($pts >= 200) return 'Level 4: Master Ambis 🚀';
        if ($pts >= 100) return 'Level 3: Mahasiswa Rajin ⭐️';
        if ($pts >= 50) return 'Level 2: Pejuang Tugas 💪';
        return 'Level 1: Pemula 🌱';
    }

    public function getNextLevelPointsAttribute(): int
    {
        $pts = $this->points ?? 0;
        if ($pts >= 350) return 500;
        if ($pts >= 200) return 350;
        if ($pts >= 100) return 200;
        if ($pts >= 50) return 100;
        return 50;
    }

    public function addPoints(int $addedPoints): array
    {
        $oldLevel = $this->level ?? 1;
        $this->points = ($this->points ?? 0) + $addedPoints;
        
        $pts = $this->points;
        if ($pts >= 350) $newLevel = 5;
        elseif ($pts >= 200) $newLevel = 4;
        elseif ($pts >= 100) $newLevel = 3;
        elseif ($pts >= 50) $newLevel = 2;
        else $newLevel = 1;

        $leveledUp = $newLevel > $oldLevel;
        $this->level = $newLevel;
        $this->save();

        return [
            'added_points' => $addedPoints,
            'total_points' => $this->points,
            'level' => $this->level,
            'level_title' => $this->level_title,
            'leveled_up' => $leveledUp,
        ];
    }

    public static function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        } elseif (! str_starts_with($digits, '62')) {
            $digits = '62'.$digits;
        }

        return $digits;
    }
}
