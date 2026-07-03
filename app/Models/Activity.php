<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'mahasiswa_id',
        'activity_text',
        'status_tugas',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
