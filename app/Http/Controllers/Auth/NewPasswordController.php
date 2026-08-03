<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $email = session('reset_email') ?? $request->email;
        if (!$email) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-otp', ['email' => $email]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'numeric', 'digits:6'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $record = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || !\Illuminate\Support\Facades\Hash::check($request->otp, $record->token)) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau salah.']);
        }

        // Check expiration (60 mins)
        if (\Carbon\Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa. Silakan minta ulang.']);
        }

        // Update password
        $user = \App\Models\Mahasiswa::where('email', $request->email)->first();
        $user->forceFill([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password)
        ])->save();

        // Delete token
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Password Anda berhasil diubah! Silakan login dengan password baru.');
    }
}
