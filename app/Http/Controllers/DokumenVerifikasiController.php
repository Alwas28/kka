<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Mahasiswa;
use App\Models\MahasiswaDokumen;
use App\Models\MahasiswaNotifikasi;
use App\Models\MahasiswaPendaftaran;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DokumenVerifikasiController extends Controller
{
    /**
     * Tampilkan daftar Bukti Pembayaran untuk diverifikasi.
     */
    public function pembayaran(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.dokumen-pembayaran'), 403);

        $query = $this->getDokumenQuery('%pembayaran%');
        $this->applyDokumenFilters($query, $request);
        $dokumenList = $query->paginate(15)->withQueryString();
        $prodiList   = $this->isAllProdi() ? ProgramStudi::orderBy('nama')->get() : collect();

        return view('dokumen.verifikasi', [
            'dokumenList'    => $dokumenList,
            'title'          => 'Bukti Pembayaran',
            'icon'           => 'fa-money-bill-wave',
            'canVerifikasi'  => auth()->user()->hasAccess('verifikasi.dokumen-pembayaran'),
            'routeTerima'    => 'dokumen.pembayaran.terima',
            'routeTolak'     => 'dokumen.pembayaran.tolak',
            'isAllProdi'     => $this->isAllProdi(),
            'prodiList'      => $prodiList,
        ]);
    }

    /**
     * Tampilkan daftar Sertifikat Baca Quran untuk diverifikasi.
     */
    public function sertifikat(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.sertifikat'), 403);

        $query = $this->getDokumenQuery('%sertifikat%', '%quran%');
        $this->applyDokumenFilters($query, $request);
        $dokumenList = $query->paginate(15)->withQueryString();
        $prodiList   = $this->isAllProdi() ? ProgramStudi::orderBy('nama')->get() : collect();

        return view('dokumen.verifikasi', [
            'dokumenList'    => $dokumenList,
            'title'          => 'Sertifikat Baca Quran',
            'icon'           => 'fa-quran',
            'canVerifikasi'  => auth()->user()->hasAccess('verifikasi.sertifikat'),
            'routeTerima'    => 'dokumen.sertifikat.terima',
            'routeTolak'     => 'dokumen.sertifikat.tolak',
            'isAllProdi'     => $this->isAllProdi(),
            'prodiList'      => $prodiList,
        ]);
    }

    /**
     * Tampilkan daftar Dokumen Lainnya untuk diverifikasi.
     * (selain pembayaran dan sertifikat baca quran)
     */
    public function dokumenLainnya(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.dokumen-lainnya'), 403);

        $query = $this->getDokumenQueryExclude('%pembayaran%', '%sertifikat%', '%quran%');
        $this->applyDokumenFilters($query, $request);
        $dokumenList = $query->paginate(15)->withQueryString();
        $prodiList   = $this->isAllProdi() ? ProgramStudi::orderBy('nama')->get() : collect();

        return view('dokumen.verifikasi', [
            'dokumenList'    => $dokumenList,
            'title'          => 'Verifikasi Dokumen',
            'icon'           => 'fa-file-alt',
            'canVerifikasi'  => auth()->user()->hasAccess('verifikasi.dokumen-lainnya'),
            'routeTerima'    => 'dokumen.lainnya.terima',
            'routeTolak'     => 'dokumen.lainnya.tolak',
            'isAllProdi'     => $this->isAllProdi(),
            'prodiList'      => $prodiList,
        ]);
    }

    /**
     * Tampilkan daftar mahasiswa yang sudah terverifikasi (level >= 5).
     */
    public function terverifikasi(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.terverifikasi'), 403);

        $baseQuery = Mahasiswa::with([
                'programStudi.fakultas',
                'level',
                'pendaftaran.kegiatan',
                'pendaftaran.dokumen.kegiatanDokumen',
            ])
            ->where('mahasiswa_level_id', '>=', 5);

        if (!$this->isAllProdi()) {
            $baseQuery->whereIn('program_studi_id', $this->prodiIds());
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $baseQuery->where(fn($q) => $q->where('nama', 'like', "%$s%")->orWhere('nim', 'like', "%$s%"));
        }
        if ($this->isAllProdi() && $request->filled('prodi')) {
            $baseQuery->where('program_studi_id', $request->prodi);
        }
        if ($request->filled('kegiatan')) {
            $baseQuery->whereHas('pendaftaran', fn($q) => $q->where('kegiatan_id', $request->kegiatan));
        }

        // Stats from full filtered dataset
        $statTotal    = (clone $baseQuery)->count();
        $statProdi    = (clone $baseQuery)->distinct()->count('program_studi_id');
        $statKegiatan = MahasiswaPendaftaran::whereIn(
            'mahasiswa_id', (clone $baseQuery)->pluck('id')
        )->whereNotNull('kegiatan_id')->distinct()->count('kegiatan_id');

        $mahasiswaList = (clone $baseQuery)->latest()->paginate(15)->withQueryString();
        $prodiList     = $this->isAllProdi() ? ProgramStudi::orderBy('nama')->get() : collect();
        $kegiatanList  = Kegiatan::orderBy('nama')->get();

        return view('dokumen.terverifikasi', [
            'mahasiswaList' => $mahasiswaList,
            'isAllProdi'    => $this->isAllProdi(),
            'canEdit'       => auth()->user()->hasAccess('edit.terverifikasi'),
            'statTotal'     => $statTotal,
            'statProdi'     => $statProdi,
            'statKegiatan'  => $statKegiatan,
            'prodiList'     => $prodiList,
            'kegiatanList'  => $kegiatanList,
        ]);
    }

    /**
     * Kembalikan mahasiswa terverifikasi ke level 3 (verifikasi ulang).
     * Reset semua dokumen ke status pending.
     */
    public function revertVerifikasi(\App\Models\Mahasiswa $mahasiswa)
    {
        abort_unless(auth()->user()->hasAccess('edit.terverifikasi'), 403);

        // Pastikan mahasiswa memang sudah terverifikasi
        abort_if($mahasiswa->mahasiswa_level_id < 5, 422, 'Mahasiswa belum terverifikasi.');

        // Otorisasi prodi
        if (!$this->isAllProdi()) {
            abort_unless(
                in_array($mahasiswa->program_studi_id, $this->prodiIds()),
                403
            );
        }

        $pendaftaran = $mahasiswa->pendaftaran;
        if ($pendaftaran) {
            // Reset semua dokumen ke pending
            MahasiswaDokumen::where('mahasiswa_pendaftaran_id', $pendaftaran->id)
                ->update([
                    'status'             => 'pending',
                    'catatan_verifikasi' => null,
                ]);

            // Set pendaftaran kembali ke submitted
            $pendaftaran->update([
                'status'       => 'submitted',
                'submitted_at' => now(),
            ]);
        }

        // Kembalikan ke level 3
        $mahasiswa->update(['mahasiswa_level_id' => 3]);

        MahasiswaNotifikasi::create([
            'mahasiswa_id' => $mahasiswa->id,
            'judul'        => 'Verifikasi Ulang',
            'pesan'        => 'Dokumen pendaftaran Anda sedang diverifikasi ulang oleh panitia.',
            'ikon'         => 'fa-rotate-left',
            'warna'        => '#f59e0b',
            'url'          => route('mahasiswa.pendaftaran.form'),
        ]);

        return back()->with('success', 'Mahasiswa "' . $mahasiswa->nama . '" dikembalikan ke tahap verifikasi ulang.');
    }

    /**
     * Terima dokumen (set status = diterima).
     */
    public function terima(MahasiswaDokumen $dokumen)
    {
        abort_unless(
            auth()->user()->hasAccess('verifikasi.dokumen-pembayaran')
            || auth()->user()->hasAccess('verifikasi.sertifikat')
            || auth()->user()->hasAccess('verifikasi.dokumen-lainnya'),
            403
        );

        $this->authorizeProdi($dokumen);

        $dokumen->update([
            'status'              => 'diterima',
            'catatan_verifikasi'  => null,
        ]);

        $namaDok = $dokumen->kegiatanDokumen?->nama ?? 'Dokumen';
        $mahasiswaId = $dokumen->pendaftaran?->mahasiswa_id;

        if ($mahasiswaId) {
            MahasiswaNotifikasi::create([
                'mahasiswa_id' => $mahasiswaId,
                'judul'        => 'Dokumen Diterima',
                'pesan'        => 'Dokumen "' . $namaDok . '" Anda telah diterima dan diverifikasi.',
                'ikon'         => 'fa-circle-check',
                'warna'        => '#10b981',
                'url'          => route('mahasiswa.pendaftaran.form'),
            ]);
        }

        $this->checkAllVerified($dokumen);

        return back()->with('success', 'Dokumen "' . $namaDok . '" diterima.');
    }

    /**
     * Tolak dokumen (set status = ditolak + catatan).
     */
    public function tolak(Request $request, MahasiswaDokumen $dokumen)
    {
        abort_unless(
            auth()->user()->hasAccess('verifikasi.dokumen-pembayaran')
            || auth()->user()->hasAccess('verifikasi.sertifikat')
            || auth()->user()->hasAccess('verifikasi.dokumen-lainnya'),
            403
        );

        $this->authorizeProdi($dokumen);

        $request->validate([
            'catatan_verifikasi' => 'required|string|max:500',
        ], [
            'catatan_verifikasi.required' => 'Catatan penolakan wajib diisi.',
        ]);

        $dokumen->update([
            'status'             => 'ditolak',
            'catatan_verifikasi' => $request->catatan_verifikasi,
        ]);

        $namaDok = $dokumen->kegiatanDokumen?->nama ?? 'Dokumen';
        $mahasiswaId = $dokumen->pendaftaran?->mahasiswa_id;

        if ($mahasiswaId) {
            MahasiswaNotifikasi::create([
                'mahasiswa_id' => $mahasiswaId,
                'judul'        => 'Dokumen Ditolak',
                'pesan'        => 'Dokumen "' . $namaDok . '" ditolak. Alasan: ' . $request->catatan_verifikasi,
                'ikon'         => 'fa-circle-xmark',
                'warna'        => '#ef4444',
                'url'          => route('mahasiswa.pendaftaran.form'),
            ]);
        }

        $this->checkAllVerified($dokumen);

        return back()->with('success', 'Dokumen "' . $namaDok . '" ditolak.');
    }

    /* ─────────────────────────────────────────── helpers ──── */

    /** Terapkan filter search, status, prodi dari request ke query dokumen */
    private function applyDokumenFilters($query, Request $request): void
    {
        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('pendaftaran.mahasiswa', fn($q) =>
                $q->where('nama', 'like', "%$s%")->orWhere('nim', 'like', "%$s%")
            );
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($this->isAllProdi() && $request->filled('prodi')) {
            $query->whereHas('pendaftaran.mahasiswa',
                fn($q) => $q->where('program_studi_id', $request->prodi)
            );
        }
    }

    /** Apakah user bisa melihat semua prodi (tidak ada prodi terkait, atau punya role Administrator) */
    private function isAllProdi(): bool
    {
        $user = auth()->user();
        if ($user->programStudi()->doesntExist()) {
            return true;
        }
        return $user->roles()->where('nama', 'Administrator')->exists();
    }

    /** IDs prodi yang dikaitkan ke user */
    private function prodiIds(): array
    {
        return auth()->user()->programStudi()->pluck('program_studi.id')->toArray();
    }

    /**
     * Query dasar: dokumen dari pendaftaran yang sudah submitted,
     * difilter by nama kegiatan_dokumen (keyword) dan prodi user.
     */
    private function getDokumenQuery(string ...$keywords)
    {
        $q = MahasiswaDokumen::with([
                'pendaftaran.mahasiswa.programStudi',
                'kegiatanDokumen.kegiatan',
            ])
            ->whereHas('pendaftaran', fn($q) => $q->where('status', 'submitted'))
            ->whereHas('kegiatanDokumen', function ($q) use ($keywords) {
                $q->where(function ($inner) use ($keywords) {
                    foreach ($keywords as $kw) {
                        $inner->orWhere('nama', 'like', $kw);
                    }
                });
            })
            ->orderByDesc('updated_at');

        if (!$this->isAllProdi()) {
            $prodiIds = $this->prodiIds();
            $q->whereHas('pendaftaran.mahasiswa',
                fn($q2) => $q2->whereIn('program_studi_id', $prodiIds)
            );
        }

        return $q;
    }

    /**
     * Query dasar: dokumen dari pendaftaran yang sudah submitted,
     * KECUALI yang namanya mengandung keyword tertentu.
     */
    private function getDokumenQueryExclude(string ...$excludeKeywords)
    {
        $q = MahasiswaDokumen::with([
                'pendaftaran.mahasiswa.programStudi',
                'kegiatanDokumen.kegiatan',
            ])
            ->whereHas('pendaftaran', fn($q) => $q->where('status', 'submitted'))
            ->whereHas('kegiatanDokumen', function ($q) use ($excludeKeywords) {
                foreach ($excludeKeywords as $kw) {
                    $q->where('nama', 'not like', $kw);
                }
            })
            ->orderByDesc('updated_at');

        if (!$this->isAllProdi()) {
            $prodiIds = $this->prodiIds();
            $q->whereHas('pendaftaran.mahasiswa',
                fn($q2) => $q2->whereIn('program_studi_id', $prodiIds)
            );
        }

        return $q;
    }

    /**
     * Cek apakah semua dokumen pendaftaran sudah diverifikasi (tidak ada pending).
     * Jika semua terverifikasi → naikkan level ke 4 + kirim notifikasi.
     */
    private function checkAllVerified(MahasiswaDokumen $dokumen): void
    {
        $pendaftaran = $dokumen->pendaftaran;
        if (!$pendaftaran) return;

        $mahasiswa = $pendaftaran->mahasiswa;
        if (!$mahasiswa || $mahasiswa->mahasiswa_level_id != 3) return;

        // Masih ada dokumen pending? Belum selesai verifikasi.
        $hasPending = MahasiswaDokumen::where('mahasiswa_pendaftaran_id', $pendaftaran->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) return;

        $hasRejected = MahasiswaDokumen::where('mahasiswa_pendaftaran_id', $pendaftaran->id)
            ->where('status', 'ditolak')
            ->exists();

        if ($hasRejected) {
            // Ada dokumen ditolak → level 4 (perbaiki dokumen)
            $mahasiswa->update(['mahasiswa_level_id' => 4]);

            MahasiswaNotifikasi::create([
                'mahasiswa_id' => $mahasiswa->id,
                'judul'        => 'Perbaiki Dokumen',
                'pesan'        => 'Beberapa dokumen Anda ditolak. Silakan perbaiki dan upload ulang, lalu kirim kembali pendaftaran.',
                'ikon'         => 'fa-triangle-exclamation',
                'warna'        => '#f59e0b',
                'url'          => route('mahasiswa.pendaftaran.form'),
            ]);
        } else {
            // Semua dokumen diterima → level 5 (selesai)
            $mahasiswa->update(['mahasiswa_level_id' => 5]);

            MahasiswaNotifikasi::create([
                'mahasiswa_id' => $mahasiswa->id,
                'judul'        => 'Semua Dokumen Diterima',
                'pesan'        => 'Selamat! Semua dokumen pendaftaran Anda telah diterima dan diverifikasi.',
                'ikon'         => 'fa-circle-check',
                'warna'        => '#10b981',
                'url'          => route('mahasiswa.pendaftaran.form'),
            ]);
        }
    }

    /** Pastikan dokumen milik mahasiswa prodi yang dipegang user */
    private function authorizeProdi(MahasiswaDokumen $dokumen): void
    {
        if ($this->isAllProdi()) {
            return;
        }

        $prodiId = $dokumen->pendaftaran?->mahasiswa?->program_studi_id;
        abort_unless(in_array($prodiId, $this->prodiIds()), 403);
    }
}
