<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\MahasiswaNotifikasi;
use App\Models\Pegawai;
use App\Models\SurveyLokasi;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    private function authorizeSetup(): void
    {
        abort_unless(auth()->user()->hasAccess('atur.kelompok'), 403);
    }

    /**
     * Halaman setup kelompok: peserta + dosen pembimbing lapangan.
     */
    public function setup(SurveyLokasi $survey)
    {
        $this->authorizeSetup();
        abort_unless($survey->kelompok !== null, 404, 'Lokasi ini belum memiliki nomor kelompok.');

        $survey->load([
            'desa.kecamatan.kabupaten.provinsi',
            'kegiatan',
            'peserta.programStudi',
            'dosenPembimbing',
        ]);

        // Mahasiswa eligible: sudah submit pendaftaran DAN belum masuk kelompok manapun
        $mahasiswaEligible = Mahasiswa::with('programStudi')
            ->whereHas('pendaftaran', fn($q) => $q->where('status', 'submitted'))
            ->whereNotIn('id', fn($q) => $q->from('kelompok_mahasiswa')->select('mahasiswa_id'))
            ->orderBy('nama')
            ->get();

        // Semua pegawai aktif
        $pegawaiList = Pegawai::where('is_active', true)->orderBy('nama')->get();

        return view('survey.setup-kelompok', compact('survey', 'mahasiswaEligible', 'pegawaiList'));
    }

    /**
     * Tambah mahasiswa ke kelompok, naikkan level ke 6, kirim notifikasi.
     */
    public function tambahMahasiswa(Request $request, SurveyLokasi $survey)
    {
        $this->authorizeSetup();

        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id',
        ]);

        $mahasiswaId = $request->mahasiswa_id;

        // Cek apakah sudah ada di kelompok manapun (bukan hanya kelompok ini)
        $sudahDiKelompok = \DB::table('kelompok_mahasiswa')
            ->where('mahasiswa_id', $mahasiswaId)
            ->exists();

        if ($sudahDiKelompok) {
            return back()->with('error', 'Mahasiswa ini sudah terdaftar di kelompok lain dan tidak dapat ditambahkan lagi.');
        }

        $survey->peserta()->attach($mahasiswaId, ['is_koordinator' => false]);

        $mahasiswa = Mahasiswa::find($mahasiswaId);
        $survey->loadMissing('desa');

        $namaDesa    = $survey->desa?->nama ?? 'lokasi KKA';
        $noKelompok  = $survey->kelompok;

        // Naikkan level ke 6 (Kelompok Tersedia) jika masih di level 5
        if ($mahasiswa->mahasiswa_level_id == 5) {
            $mahasiswa->update(['mahasiswa_level_id' => 6]);
        }

        // Kirim notifikasi
        MahasiswaNotifikasi::create([
            'mahasiswa_id' => $mahasiswaId,
            'judul'        => 'Kelompok KKA Tersedia',
            'pesan'        => "Anda telah ditambahkan ke Kelompok {$noKelompok} dengan lokasi di Desa {$namaDesa}. Segera cek informasi kelompok Anda di dashboard.",
            'ikon'         => 'fa-users',
            'warna'        => '#10b981',
            'url'          => route('mahasiswa.dashboard'),
        ]);

        return back()->with('success', 'Mahasiswa berhasil ditambahkan ke kelompok dan notifikasi telah dikirim.');
    }

    /**
     * Hapus mahasiswa dari kelompok.
     */
    public function hapusMahasiswa(SurveyLokasi $survey, Mahasiswa $mahasiswa)
    {
        $this->authorizeSetup();

        $survey->peserta()->detach($mahasiswa->id);

        return back()->with('success', 'Mahasiswa berhasil dikeluarkan dari kelompok.');
    }

    /**
     * Toggle status koordinator mahasiswa dalam kelompok.
     */
    public function setKoordinator(Request $request, SurveyLokasi $survey, Mahasiswa $mahasiswa)
    {
        $this->authorizeSetup();

        $isKoordinator = $request->boolean('is_koordinator');

        // Jika jadikan koordinator, hapus koordinator lama dulu
        if ($isKoordinator) {
            $survey->peserta()->updateExistingPivot(
                $survey->peserta()->allRelatedIds()->toArray(),
                ['is_koordinator' => false]
            );
        }

        $survey->peserta()->updateExistingPivot($mahasiswa->id, [
            'is_koordinator' => $isKoordinator,
        ]);

        return response()->json(['success' => true, 'is_koordinator' => $isKoordinator]);
    }

    /**
     * Tambah dosen pembimbing lapangan ke kelompok.
     */
    public function tambahDosen(Request $request, SurveyLokasi $survey)
    {
        $this->authorizeSetup();

        $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
        ]);

        $pegawaiId = $request->pegawai_id;

        if ($survey->dosenPembimbing()->where('pegawai_id', $pegawaiId)->exists()) {
            return back()->with('error', 'Dosen sudah ditambahkan ke kelompok ini.');
        }

        $survey->dosenPembimbing()->attach($pegawaiId);

        return back()->with('success', 'Dosen Pembimbing Lapangan berhasil ditambahkan.');
    }

    /**
     * Hapus dosen pembimbing dari kelompok.
     */
    public function hapusDosen(SurveyLokasi $survey, Pegawai $pegawai)
    {
        $this->authorizeSetup();

        $survey->dosenPembimbing()->detach($pegawai->id);

        return back()->with('success', 'Dosen Pembimbing berhasil dikeluarkan dari kelompok.');
    }
}
