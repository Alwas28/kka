<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;


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
        $isAllProdi = $user->programStudi->isEmpty();

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
        $isAllProdi = $user->programStudi->isEmpty();

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

        return back()->with('success', "Registrasi <strong>{$mahasiswa->nama}</strong> telah disetujui. Mahasiswa dapat melanjutkan pengisian pendaftaran.");
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
     * Pastikan mahasiswa yang divalidasi berada di prodi yang menjadi tanggung jawab user.
     * Jika user tidak memiliki prodi (administrator), semua prodi diizinkan.
     */
    private function otorisasiProdi(Mahasiswa $mahasiswa): void
    {
        $user = auth()->user();
        $user->loadMissing('programStudi');

        if ($user->programStudi->isNotEmpty()) {
            abort_unless(
                $user->programStudi->pluck('id')->contains($mahasiswa->program_studi_id),
                403,
                'Anda tidak berwenang memvalidasi mahasiswa dari program studi ini.'
            );
        }
    }
}
