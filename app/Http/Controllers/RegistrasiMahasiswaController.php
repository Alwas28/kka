<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RegistrasiMahasiswaController extends Controller
{
    /**
     * Daftar mahasiswa yang sedang menunggu persetujuan prodi (level 1).
     * Difilter berdasarkan program studi yang ditangani user.
     * Jika user tidak memiliki prodi terkait → tampilkan semua.
     */
    public function index(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.registrasi'), 403, 'Anda tidak memiliki akses untuk melihat halaman ini.');

        $user = auth()->user();
        $user->load('programStudi');
        $isAllProdi = $this->isAllProdi($user);

        $query = Mahasiswa::with(['programStudi.fakultas', 'level'])
            ->where('mahasiswa_level_id', 1);

        if (! $isAllProdi) {
            $query->whereIn('program_studi_id', $user->programStudi->pluck('id'));
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nama', 'like', "%$s%")
                ->orWhere('nim', 'like', "%$s%")
                ->orWhere('email', 'like', "%$s%"));
        }

        if ($isAllProdi && $request->filled('prodi')) {
            $query->where('program_studi_id', $request->prodi);
        }

        $mahasiswaList = $query->latest()->paginate(15)->withQueryString();
        $prodiList     = $isAllProdi ? ProgramStudi::orderBy('nama')->get() : collect();

        return view('registrasi.index', compact('mahasiswaList', 'isAllProdi', 'prodiList'));
    }

    /**
     * Daftar mahasiswa yang sudah disetujui prodi (level 2) dan sedang mengisi form pendaftaran.
     */
    public function disetujui(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.registrasi'), 403, 'Anda tidak memiliki akses untuk melihat halaman ini.');

        $user = auth()->user();
        $user->load('programStudi');
        $isAllProdi = $this->isAllProdi($user);

        $query = Mahasiswa::with(['programStudi.fakultas', 'level', 'pendaftaran'])
            ->where('mahasiswa_level_id', 2);

        if (! $isAllProdi) {
            $query->whereIn('program_studi_id', $user->programStudi->pluck('id'));
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nama', 'like', "%$s%")
                ->orWhere('nim', 'like', "%$s%")
                ->orWhere('email', 'like', "%$s%"));
        }

        if ($isAllProdi && $request->filled('prodi')) {
            $query->where('program_studi_id', $request->prodi);
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'belum') {
                $query->doesntHave('pendaftaran');
            } elseif ($status === 'draft') {
                $query->whereHas('pendaftaran', fn($q) => $q->where('status', 'draft'));
            } elseif ($status === 'submitted') {
                $query->whereHas('pendaftaran', fn($q) => $q->where('status', 'submitted'));
            }
        }

        $mahasiswaList = $query->latest()->paginate(15)->withQueryString();
        $prodiList     = ProgramStudi::with('fakultas')->orderBy('nama')->get();
        $kegiatanList  = Kegiatan::orderByDesc('id')->get();

        return view('registrasi.disetujui', compact('mahasiswaList', 'isAllProdi', 'prodiList', 'kegiatanList'));
    }

    /**
     * Setujui registrasi → naikkan ke level 2 (Disetujui Prodi).
     */
    public function setujui(Mahasiswa $mahasiswa)
    {
        abort_unless(auth()->user()->hasAccess('validasi.register'), 403, 'Anda tidak memiliki akses untuk memvalidasi registrasi.');
        $this->otorisasiProdi($mahasiswa);

        abort_if($mahasiswa->mahasiswa_level_id !== 1, 422, 'Registrasi ini sudah diproses.');

        $mahasiswa->update(['mahasiswa_level_id' => 2]);

        DB::table('mahasiswa_notifikasi')->insert([
            'mahasiswa_id' => $mahasiswa->id,
            'judul'        => 'Registrasi Disetujui',
            'pesan'        => 'Registrasi KKA Anda telah disetujui oleh Program Studi. Silakan lengkapi form pendaftaran.',
            'ikon'         => 'fa-check-circle',
            'warna'        => '#059669',
            'url'          => null,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return back()->with('success', "Registrasi <strong>{$mahasiswa->nama}</strong> telah disetujui. Mahasiswa dapat melanjutkan pengisian pendaftaran.");
    }

    /**
     * Batalkan persetujuan → kembalikan ke level 1 (Registrasi).
     */
    public function kembalikan(Mahasiswa $mahasiswa)
    {
        abort_unless(auth()->user()->hasAccess('validasi.register'), 403, 'Anda tidak memiliki akses untuk memvalidasi registrasi.');
        $this->otorisasiProdi($mahasiswa);

        abort_if($mahasiswa->mahasiswa_level_id !== 2, 422, 'Mahasiswa ini tidak berada di level Disetujui Prodi.');

        $mahasiswa->update(['mahasiswa_level_id' => 1]);

        DB::table('mahasiswa_notifikasi')->insert([
            'mahasiswa_id' => $mahasiswa->id,
            'judul'        => 'Registrasi Dikembalikan',
            'pesan'        => 'Persetujuan registrasi KKA Anda telah dibatalkan oleh Program Studi. Silakan hubungi prodi untuk informasi lebih lanjut.',
            'ikon'         => 'fa-rotate-left',
            'warna'        => '#dc2626',
            'url'          => null,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return back()->with('success', "Persetujuan <strong>{$mahasiswa->nama}</strong> telah dibatalkan. Mahasiswa dikembalikan ke daftar registrasi.");
    }

    /**
     * Tolak registrasi → hapus data mahasiswa (dapat mendaftar ulang).
     */
    public function tolak(Request $request, Mahasiswa $mahasiswa)
    {
        abort_unless(auth()->user()->hasAccess('validasi.register'), 403, 'Anda tidak memiliki akses untuk memvalidasi registrasi.');
        $this->otorisasiProdi($mahasiswa);

        abort_if($mahasiswa->mahasiswa_level_id !== 1, 422, 'Registrasi ini sudah diproses.');

        $nama = $mahasiswa->nama;
        $mahasiswa->delete();

        return back()->with('success', "Registrasi <strong>{$nama}</strong> telah ditolak. Data dihapus dan mahasiswa dapat mendaftar ulang.");
    }

    // ────────────────────────────────────────────
    // PRIVATE HELPER
    // ────────────────────────────────────────────

    /**
     * Apakah user bisa melihat semua prodi:
     * - tidak ada prodi terkait, ATAU
     * - punya role Administrator
     */
    private function isAllProdi($user = null): bool
    {
        $user = $user ?? auth()->user();
        $user->loadMissing('programStudi');
        if ($user->programStudi->isEmpty()) {
            return true;
        }
        return $user->roles()->where('nama', 'Administrator')->exists();
    }

    /**
     * Pastikan mahasiswa yang divalidasi berada di prodi yang menjadi tanggung jawab user.
     * Jika user Administrator atau tidak memiliki prodi, semua prodi diizinkan.
     */
    private function otorisasiProdi(Mahasiswa $mahasiswa): void
    {
        $user = auth()->user();
        if ($this->isAllProdi($user)) {
            return;
        }

        abort_unless(
            $user->programStudi->pluck('id')->contains($mahasiswa->program_studi_id),
            403,
            'Anda tidak berwenang memvalidasi mahasiswa dari program studi ini.'
        );
    }
}
