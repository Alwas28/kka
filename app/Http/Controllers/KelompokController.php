<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\MahasiswaNotifikasi;
use App\Models\Pegawai;
use App\Models\SurveyLokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KelompokController extends Controller
{
    // Semua kolom yang bisa dipilih untuk export peserta
    private const EXPORT_COLUMNS = [
        'no'             => 'No',
        'nim'            => 'NIM',
        'nama'           => 'Nama Mahasiswa',
        'email'          => 'Email',
        'prodi'          => 'Program Studi',
        'jenis_kelamin'  => 'Jenis Kelamin',
        'tempat_lahir'   => 'Tempat Lahir',
        'tanggal_lahir'  => 'Tanggal Lahir',
        'no_hp'          => 'No. HP',
        'golongan_darah' => 'Gol. Darah',
        'alamat'         => 'Alamat',
        'semester'       => 'Semester',
        'sks_ditempuh'   => 'SKS Ditempuh',
        'ipk'            => 'IPK',
        'ukuran_baju'    => 'Ukuran Baju',
        'koordinator'    => 'Koordinator',
    ];

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

        $exportColumns = self::EXPORT_COLUMNS;

        return view('survey.setup-kelompok', compact('survey', 'mahasiswaEligible', 'pegawaiList', 'exportColumns'));
    }

    /**
     * Export daftar peserta kelompok ini ke Excel, dengan kolom identitas yang bisa dipilih.
     */
    public function exportPeserta(Request $request, SurveyLokasi $survey)
    {
        $this->authorizeSetup();

        $allColumns = self::EXPORT_COLUMNS;

        $selectedColumns = collect($request->input('kolom', array_keys($allColumns)))
            ->filter(fn($c) => isset($allColumns[$c]))
            ->values()
            ->toArray();

        if (empty($selectedColumns)) {
            $selectedColumns = array_keys($allColumns);
        }

        $rows = DB::table('kelompok_mahasiswa as km')
            ->join('mahasiswa as m', 'm.id', '=', 'km.mahasiswa_id')
            ->leftJoin('program_studi as ps', 'ps.id', '=', 'm.program_studi_id')
            ->leftJoin('mahasiswa_pendaftaran as mp', 'mp.mahasiswa_id', '=', 'm.id')
            ->where('km.survey_lokasi_id', $survey->id)
            ->select([
                'm.nim', 'm.nama', 'm.email',
                'ps.nama as prodi_nama',
                'km.is_koordinator',
                'mp.jenis_kelamin', 'mp.tempat_lahir', 'mp.tanggal_lahir', 'mp.no_hp',
                'mp.golongan_darah', 'mp.alamat', 'mp.semester', 'mp.sks_ditempuh',
                'mp.ipk', 'mp.ukuran_baju',
            ])
            ->orderByDesc('km.is_koordinator')
            ->orderBy('m.nama')
            ->get();

        $survey->loadMissing('desa');
        $namaKelompok = 'Kelompok ' . $survey->kelompok . ($survey->desa ? ' - ' . $survey->desa->nama : '');
        $filename     = 'peserta-' . Str::slug($namaKelompok) . '-' . date('Ymd') . '.xls';
        $colspan      = count($selectedColumns);
        $exportedAt   = now()->format('d/m/Y H:i');

        $html  = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>';
        $html .= '<table border="1" cellspacing="0" cellpadding="0" style="font-family:Arial,sans-serif;font-size:11px;border-collapse:collapse;">';
        $html .= '<tr><td colspan="' . $colspan . '" style="font-weight:bold;font-size:13px;background:#8B0000;color:#fff;padding:10px 14px;">'
               . htmlspecialchars("Daftar Peserta \u{2014} {$namaKelompok}") . '</td></tr>';
        $html .= '<tr><td colspan="' . $colspan . '" style="font-size:10px;color:#555;padding:5px 14px;background:#fef2f2;">'
               . htmlspecialchars("Diekspor: {$exportedAt}  |  Total: {$rows->count()} peserta")
               . '</td></tr>';
        $html .= '<tr><td colspan="' . $colspan . '" style="padding:3px;"></td></tr>';

        $html .= '<tr>';
        foreach ($selectedColumns as $col) {
            $html .= '<th style="background:#8B0000;color:#fff;font-weight:bold;padding:7px 10px;text-align:left;border:1px solid #a00;">'
                   . htmlspecialchars($allColumns[$col]) . '</th>';
        }
        $html .= '</tr>';

        foreach ($rows as $i => $mhs) {
            $bg    = $i % 2 === 0 ? '#ffffff' : '#fafafa';
            $html .= '<tr style="background:' . $bg . ';">';
            foreach ($selectedColumns as $col) {
                $val = $this->resolveExportColumn($col, $mhs, $i);
                $html .= '<td style="padding:5px 8px;border:1px solid #ddd;">' . htmlspecialchars((string) $val) . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</table></body></html>';

        return response($html, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }

    private function resolveExportColumn(string $col, object $mhs, int $i): string
    {
        return match ($col) {
            'no'             => (string) ($i + 1),
            'nim'            => $mhs->nim ?? '-',
            'nama'           => $mhs->nama ?? '-',
            'email'          => $mhs->email ?? '-',
            'prodi'          => $mhs->prodi_nama ?? '-',
            'jenis_kelamin'  => match ($mhs->jenis_kelamin ?? '') {
                'L' => 'Laki-laki', 'P' => 'Perempuan', default => '-',
            },
            'tempat_lahir'   => $mhs->tempat_lahir ?? '-',
            'tanggal_lahir'  => $mhs->tanggal_lahir
                ? \Carbon\Carbon::parse($mhs->tanggal_lahir)->format('d/m/Y')
                : '-',
            'no_hp'          => $mhs->no_hp ?? '-',
            'golongan_darah' => $mhs->golongan_darah ?? '-',
            'alamat'         => $mhs->alamat ?? '-',
            'semester'       => $mhs->semester !== null ? (string) $mhs->semester : '-',
            'sks_ditempuh'   => $mhs->sks_ditempuh !== null ? (string) $mhs->sks_ditempuh : '-',
            'ipk'            => $mhs->ipk !== null ? number_format((float) $mhs->ipk, 2) : '-',
            'ukuran_baju'    => $mhs->ukuran_baju ?? '-',
            'koordinator'    => $mhs->is_koordinator ? 'Ya' : 'Tidak',
            default          => '-',
        };
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
        $sudahDiKelompok = DB::table('kelompok_mahasiswa')
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

        // Naikkan level ke 6 (Pelaksanaan) jika belum di level 6+
        if ($mahasiswa->mahasiswa_level_id < 6) {
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

        // Jika tidak lagi ada di kelompok manapun, kembalikan ke level 5
        $masihDiKelompok = DB::table('kelompok_mahasiswa')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->exists();

        if (!$masihDiKelompok && $mahasiswa->mahasiswa_level_id == 6) {
            $mahasiswa->update(['mahasiswa_level_id' => 5]);
        }

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
