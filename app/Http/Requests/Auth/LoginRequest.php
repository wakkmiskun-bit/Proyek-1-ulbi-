<?php

namespace App\Http\Requests\Auth;

use App\Models\Mahasiswa;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = $this->string('login')->trim();
        $email = Str::lower($login);

        // Check if matching Admin credentials
        $admin = \App\Models\Admin::query()->where('email', $email)->first();
        if ($admin && Hash::check($this->string('password'), $admin->password)) {
            Auth::guard('admin')->login($admin, $this->boolean('remember'));
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // Otherwise check Mahasiswa
        $mahasiswa = Mahasiswa::query()
            ->where('email', $email)
            ->orWhere('nim', $login)
            ->first();

        if (! $mahasiswa || ! Hash::check($this->string('password'), $mahasiswa->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        Auth::guard('web')->login($mahasiswa, $this->boolean('remember'));
        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}
