<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicket extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'nama',
        'email',
        'whatsapp',
        'perihal',
        'pesan',
        'status',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'baru'    => 'Baru',
            'dibaca'  => 'Dibaca',
            'dijawab' => 'Dijawab',
            default   => 'Baru',
        };
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        if (!$this->whatsapp) return null;
        $digits = preg_replace('/\D/', '', $this->whatsapp);
        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        } elseif (!str_starts_with($digits, '62')) {
            $digits = '62' . $digits;
        }
        return 'https://wa.me/' . $digits;
    }
}
