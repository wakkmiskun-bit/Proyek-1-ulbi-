<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /** Tampilkan form bantuan */
    public function create()
    {
        $user = Auth::guard('web')->user();
        return view('bantuan', compact('user'));
    }

    /** Simpan tiket bantuan dari user */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'perihal'  => ['required', 'string', 'max:255'],
            'pesan'    => ['required', 'string', 'max:3000'],
        ]);

        $user = Auth::guard('web')->user();

        SupportTicket::create([
            'mahasiswa_id' => $user?->id,
            'nama'         => $validated['nama'],
            'email'        => $validated['email'],
            'whatsapp'     => $validated['whatsapp'] ?? null,
            'perihal'      => $validated['perihal'],
            'pesan'        => $validated['pesan'],
            'status'       => 'baru',
        ]);

        return redirect()->route('bantuan')->with('success', 'Pesan Anda berhasil dikirim! Admin akan segera menghubungi Anda.');
    }

    /** Admin: daftar semua tiket bantuan (JSON) */
    public function adminIndex()
    {
        $tickets = SupportTicket::with('mahasiswa:id,nim,nama,foto')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($t) => [
                'id'           => $t->id,
                'nama'         => $t->nama,
                'email'        => $t->email,
                'whatsapp'     => $t->whatsapp,
                'whatsapp_url' => $t->whatsapp_url,
                'perihal'      => $t->perihal,
                'pesan'        => $t->pesan,
                'status'       => $t->status,
                'status_label' => $t->status_label,
                'nim'          => $t->mahasiswa?->nim,
                'time'         => $t->created_at->format('d M Y, H:i'),
                'time_human'   => $t->created_at->diffForHumans(),
            ]);

        return response()->json($tickets);
    }

    /** Admin: update status tiket */
    public function adminUpdateStatus(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:baru,dibaca,dijawab'],
        ]);

        $ticket->update($validated);

        return response()->json(['message' => 'Status diperbarui.', 'status' => $ticket->fresh()->status]);
    }

    /** Admin: hapus tiket */
    public function adminDestroy(SupportTicket $ticket)
    {
        $ticket->delete();
        return response()->json(['message' => 'Tiket dihapus.']);
    }
}
