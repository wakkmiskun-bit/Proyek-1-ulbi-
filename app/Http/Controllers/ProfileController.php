<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $mahasiswa = $request->user();
        
        $request->validate([
            'nama' => ['required_without:name', 'string', 'max:255'],
            'name' => ['required_without:nama', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:mahasiswas,email,' . $mahasiswa->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'universitas' => ['nullable', 'string', 'max:255'],
            'semester' => ['nullable', 'integer', 'min:1', 'max:8'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $mahasiswa->nama = $request->input('nama') ?? $request->input('name');
        $mahasiswa->email = $request->input('email');
        if ($request->has('phone')) {
            $mahasiswa->phone = $request->input('phone');
        }
        if ($request->has('universitas')) {
            $mahasiswa->universitas = $request->input('universitas');
        }
        if ($request->has('semester')) {
            $mahasiswa->semester = $request->input('semester');
        }

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('profile-photos', 'public');
            $mahasiswa->foto = $path;
        }

        $mahasiswa->save();

        if (str_contains(url()->previous(), 'dashboard')) {
            return redirect()->route('dashboard')->with('status', 'profile-updated');
        }

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::guard('web')->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
