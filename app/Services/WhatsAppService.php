<?php

namespace App\Services;

use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function isConfigured(): bool
    {
        return ! empty(config('services.whatsapp.token'));
    }

    public function send(string $phone, string $message): bool
    {
        if (! $this->isConfigured()) {
            Log::info('WhatsApp (simulasi): '.$phone.' — '.$message);

            return true;
        }

        $target = Mahasiswa::normalizePhone($phone);

        $response = Http::withHeaders([
            'Authorization' => config('services.whatsapp.token'),
        ])->post(config('services.whatsapp.url'), [
            'target' => $target,
            'message' => $message,
            'countryCode' => '62',
        ]);

        if (! $response->successful()) {
            Log::warning('WhatsApp gagal dikirim', [
                'phone' => $target,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        }

        return true;
    }
}
