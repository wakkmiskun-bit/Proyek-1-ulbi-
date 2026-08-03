<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $mahasiswa = \App\Models\Mahasiswa::where('email', $request->email)->first();
        if (!$mahasiswa) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Generate 6 digit OTP
        $otp = sprintf("%06d", mt_rand(1, 999999));

        // Save to password_reset_tokens table
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $mahasiswa->email],
            [
                'token' => \Illuminate\Support\Facades\Hash::make($otp),
                'created_at' => now()
            ]
        );

        // Send via WhatsApp
        $message = "Halo *{$mahasiswa->nama}*,\n\n";
        $message .= "Berikut adalah KODE RESET PASSWORD Anda:\n\n";
        $message .= "*{$otp}*\n\n";
        $message .= "Masukkan kode OTP ini di aplikasi untuk membuat password baru. Kode ini berlaku selama 60 menit.\n\n";
        $message .= "Jika Anda tidak meminta reset password, abaikan pesan ini.\n\n";
        $message .= "_Sistem Otomatis TaskMate_";

        if (!empty($mahasiswa->phone)) {
            $waService = app(\App\Services\WhatsAppService::class);
            $waService->send($mahasiswa->phone, $message);
        } else {
            \Illuminate\Support\Facades\Log::warning("OTP WA tidak terkirim, no phone for user: {$mahasiswa->email}. OTP: {$otp}");
        }

        return redirect()->route('password.verify')->with('reset_email', $mahasiswa->email);
    }
}
