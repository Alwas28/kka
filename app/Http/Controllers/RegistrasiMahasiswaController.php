<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RegistrasiMahasiswaController extends Controller
{
    /**
     * Daftar mahasiswa yang sedang menunggu persetujuan prodi (level 1).
     * Difilter berdasarkan program studi yang ditangani user.
     * Jika user tidak memiliki prodi terkait → tampilkan semua.
     */
    public function index()
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

        $mahasiswaList = $query->latest()->get();

        return view('registrasi.index', compact('mahasiswaList', 'isAllProdi'));
    }

    /**
     * Daftar mahasiswa yang sudah disetujui prodi (level 2) dan sedang mengisi form pendaftaran.
     */
    public function disetujui()
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

        $mahasiswaList = $query->latest()->get();

        return view('registrasi.disetujui', compact('mahasiswaList', 'isAllProdi'));
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
