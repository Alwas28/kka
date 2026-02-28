<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── KEGIATAN ──────────────────────────────────────────────────────────
        $totalKegiatan   = DB::table('kegiatan')->count();
        $kegiatanAktif   = DB::table('kegiatan')
            ->where('kegiatan_mulai', '<=', now())
            ->where('kegiatan_selesai', '>=', now())
            ->count();
        $kegiatanSelesai = DB::table('kegiatan')
            ->where('kegiatan_selesai', '<', now())
            ->count();

        // ── MAHASISWA ─────────────────────────────────────────────────────────
        $totalMahasiswa     = DB::table('mahasiswa')->count();
        $pendaftaranDraft   = DB::table('mahasiswa_pendaftaran')->where('status', 'draft')->count();
        $pendaftaranSubmit  = DB::table('mahasiswa_pendaftaran')->where('status', 'submitted')->count();

        // ── DOKUMEN ───────────────────────────────────────────────────────────
        $dokumenPending  = DB::table('mahasiswa_dokumen')->where('status', 'pending')->count();
        $dokumenDiterima = DB::table('mahasiswa_dokumen')->where('status', 'diterima')->count();
        $dokumenDitolak  = DB::table('mahasiswa_dokumen')->where('status', 'ditolak')->count();

        // ── SURVEY LOKASI ─────────────────────────────────────────────────────
        $surveyBelum   = DB::table('survey_lokasi')->where('status', 'belum_survey')->count();
        $surveySudah   = DB::table('survey_lokasi')->where('status', 'sudah_survey')->count();
        $surveySetuju  = DB::table('survey_lokasi')->where('status', 'disetujui')->count();
        $surveyDitolak = DB::table('survey_lokasi')->where('status', 'ditolak')->count();
        $totalSurvey   = $surveyBelum + $surveySudah + $surveySetuju + $surveyDitolak;

        // ── KELOMPOK ──────────────────────────────────────────────────────────
        $totalKelompok   = DB::table('survey_lokasi')->whereNotNull('kegiatan_id')->count();
        $totalMahasiswaKelompok = DB::table('kelompok_mahasiswa')->distinct('mahasiswa_id')->count('mahasiswa_id');

        // ── PELAKSANAAN ───────────────────────────────────────────────────────
        $totalLogbook        = DB::table('logbook')->count();
        $totalLaporanIndividu = DB::table('laporan_individu')->count();
        $totalLaporanAkhir   = DB::table('laporan_akhir')->count();
        $totalDinilai        = DB::table('nilai_mahasiswa')->whereNotNull('nilai_akhir')->count();

        // ── KEGIATAN BERLANGSUNG (with per-kegiatan stats) ────────────────────
        $today = now()->toDateString();
        $kegiatanBerlangsung = DB::table('kegiatan')
            ->leftJoin('jenis_kka', 'kegiatan.jenis_kka_id', '=', 'jenis_kka.id')
            ->leftJoin('tahun',     'kegiatan.tahun_id',     '=', 'tahun.id')
            ->where('kegiatan.kegiatan_mulai', '<=', $today)
            ->where('kegiatan.kegiatan_selesai', '>=', $today)
            ->select([
                'kegiatan.id',
                'kegiatan.nama',
                'kegiatan.kegiatan_mulai',
                'kegiatan.kegiatan_selesai',
                'jenis_kka.nama as jenis',
                'tahun.nama as tahun',
            ])
            ->orderByDesc('kegiatan.id')
            ->get();

        // Per-kegiatan aggregates
        $kegiatanIds = $kegiatanBerlangsung->pluck('id');

        $pesertaPerKegiatan = DB::table('kelompok_mahasiswa')
            ->join('survey_lokasi', 'kelompok_mahasiswa.survey_lokasi_id', '=', 'survey_lokasi.id')
            ->whereIn('survey_lokasi.kegiatan_id', $kegiatanIds)
            ->selectRaw('survey_lokasi.kegiatan_id, COUNT(DISTINCT kelompok_mahasiswa.mahasiswa_id) as total')
            ->groupBy('survey_lokasi.kegiatan_id')
            ->pluck('total', 'kegiatan_id');

        $kelompokPerKegiatan = DB::table('survey_lokasi')
            ->whereIn('kegiatan_id', $kegiatanIds)
            ->selectRaw('kegiatan_id, COUNT(*) as total')
            ->groupBy('kegiatan_id')
            ->pluck('total', 'kegiatan_id');

        $logbookPerKegiatan = DB::table('logbook')
            ->join('survey_lokasi', 'logbook.survey_lokasi_id', '=', 'survey_lokasi.id')
            ->whereIn('survey_lokasi.kegiatan_id', $kegiatanIds)
            ->selectRaw('survey_lokasi.kegiatan_id, COUNT(*) as total')
            ->groupBy('survey_lokasi.kegiatan_id')
            ->pluck('total', 'kegiatan_id');

        $nilaiPerKegiatan = DB::table('nilai_mahasiswa')
            ->join('survey_lokasi', 'nilai_mahasiswa.survey_lokasi_id', '=', 'survey_lokasi.id')
            ->whereIn('survey_lokasi.kegiatan_id', $kegiatanIds)
            ->whereNotNull('nilai_mahasiswa.nilai_akhir')
            ->selectRaw('survey_lokasi.kegiatan_id, COUNT(*) as total')
            ->groupBy('survey_lokasi.kegiatan_id')
            ->pluck('total', 'kegiatan_id');

        // Tahapan aktif per kegiatan
        $tahapanAktif = DB::table('kegiatan_tahapan')
            ->whereIn('kegiatan_id', $kegiatanIds)
            ->where('mulai', '<=', $today)
            ->where('selesai', '>=', $today)
            ->select('kegiatan_id', 'nama')
            ->get()
            ->groupBy('kegiatan_id')
            ->map(fn($rows) => $rows->pluck('nama')->first());

        // ── PENDAFTARAN TERBARU ───────────────────────────────────────────────
        $pendaftaranTerbaru = DB::table('mahasiswa_pendaftaran')
            ->join('mahasiswa', 'mahasiswa_pendaftaran.mahasiswa_id', '=', 'mahasiswa.id')
            ->join('kegiatan',  'mahasiswa_pendaftaran.kegiatan_id',  '=', 'kegiatan.id')
            ->leftJoin('program_studi', 'mahasiswa.program_studi_id', '=', 'program_studi.id')
            ->where('mahasiswa_pendaftaran.status', 'submitted')
            ->select([
                'mahasiswa.nama',
                'mahasiswa.nim',
                'program_studi.nama as prodi',
                'kegiatan.nama as kegiatan',
                'mahasiswa_pendaftaran.submitted_at',
            ])
            ->orderByDesc('mahasiswa_pendaftaran.submitted_at')
            ->limit(8)
            ->get();

        // ── DOKUMEN PENDING TERBARU ───────────────────────────────────────────
        $dokumenPendingTerbaru = DB::table('mahasiswa_dokumen')
            ->join('mahasiswa_pendaftaran', 'mahasiswa_dokumen.mahasiswa_pendaftaran_id', '=', 'mahasiswa_pendaftaran.id')
            ->join('mahasiswa',             'mahasiswa_pendaftaran.mahasiswa_id',         '=', 'mahasiswa.id')
            ->join('kegiatan_dokumen',      'mahasiswa_dokumen.kegiatan_dokumen_id',      '=', 'kegiatan_dokumen.id')
            ->where('mahasiswa_dokumen.status', 'pending')
            ->select([
                'mahasiswa.nama',
                'mahasiswa.nim',
                'kegiatan_dokumen.nama as dokumen',
                'mahasiswa_dokumen.created_at as uploaded_at',
            ])
            ->orderByDesc('mahasiswa_dokumen.created_at')
            ->limit(8)
            ->get();

        return view('dashboard', compact(
            'totalKegiatan', 'kegiatanAktif', 'kegiatanSelesai',
            'totalMahasiswa', 'pendaftaranDraft', 'pendaftaranSubmit',
            'dokumenPending', 'dokumenDiterima', 'dokumenDitolak',
            'surveyBelum', 'surveySudah', 'surveySetuju', 'surveyDitolak', 'totalSurvey',
            'totalKelompok', 'totalMahasiswaKelompok',
            'totalLogbook', 'totalLaporanIndividu', 'totalLaporanAkhir', 'totalDinilai',
            'kegiatanBerlangsung', 'pesertaPerKegiatan', 'kelompokPerKegiatan',
            'logbookPerKegiatan', 'nilaiPerKegiatan', 'tahapanAktif',
            'pendaftaranTerbaru', 'dokumenPendingTerbaru'
        ));
    }
}
