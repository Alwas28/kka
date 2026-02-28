<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaNotifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaDashboardController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $mahasiswa->load('programStudi.fakultas', 'level');

        return view('mahasiswa.dashboard', compact('mahasiswa'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('mahasiswa')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    public function readNotifikasi(MahasiswaNotifikasi $notifikasi, Request $request): RedirectResponse
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        abort_if($notifikasi->mahasiswa_id !== $mahasiswa->id, 403);

        $notifikasi->update(['read_at' => now()]);

        return redirect($request->input('redirect', route('mahasiswa.dashboard')));
    }

    public function readAllNotifikasi(): RedirectResponse
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $mahasiswa->unreadNotifikasi()->update(['read_at' => now()]);

        return back();
    }
}
