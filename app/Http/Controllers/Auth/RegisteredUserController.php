<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Services\ActivityLogger;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nim' => ['required', 'string', 'max:20', 'unique:mahasiswas,nim'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:mahasiswas,email'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'universitas' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'phone.regex' => 'Format nomor telepon tidak valid.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('photo')) {
            $fotoPath = $request->file('photo')->store('photos', 'public');
        }

        $mahasiswa = Mahasiswa::create([
            'nim' => $request->nim,
            'nama' => $request->name,
            'email' => $request->email,
            'phone' => Mahasiswa::normalizePhone($request->phone),
            'universitas' => $request->universitas,
            'foto' => $fotoPath,
            'password' => $request->password,
        ]);

        event(new Registered($mahasiswa));
        ActivityLogger::logMahasiswaRegistered($mahasiswa);

        return redirect()
            ->route('login')
            ->with('status', 'Registrasi berhasil! Silakan login untuk masuk ke dashboard.');
    }
}
