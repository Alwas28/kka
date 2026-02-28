<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\SurveyLokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesertaController extends Controller
{
    /**
     * Daftar peserta KKA yang sudah masuk kelompok (flat, paginated).
     */
    public function index(Request $request)
    {
        $kegiatanList = Kegiatan::orderByDesc('created_at')->get();

        $kegiatanId = $request->kegiatan_id;
        $kelompokNo = $request->kelompok;

        // Dropdown kelompok (dinamis sesuai kegiatan)
        $kelompokNumbers = SurveyLokasi::has('peserta')
            ->when($kegiatanId, fn($q) => $q->where('kegiatan_id', $kegiatanId))
            ->whereNotNull('kelompok')
            ->orderBy('kelompok')
            ->pluck('kelompok')
            ->unique()
            ->values();

        // Flat query → tiap baris = 1 mahasiswa
        $peserta = DB::table('kelompok_mahasiswa')
            ->join('mahasiswa',     'kelompok_mahasiswa.mahasiswa_id',     '=', 'mahasiswa.id')
            ->join('survey_lokasi', 'kelompok_mahasiswa.survey_lokasi_id', '=', 'survey_lokasi.id')
            ->leftJoin('kegiatan',      'survey_lokasi.kegiatan_id',  '=', 'kegiatan.id')
            ->leftJoin('program_studi', 'mahasiswa.program_studi_id', '=', 'program_studi.id')
            ->leftJoin('desa',          'survey_lokasi.desa_id',      '=', 'desa.id')
            ->select([
                'mahasiswa.id as mahasiswa_id',
                'mahasiswa.nama',
                'mahasiswa.nim',
                'program_studi.nama as prodi',
                'survey_lokasi.id as survey_lokasi_id',
                'survey_lokasi.kelompok',
                'desa.nama as desa',
                'kegiatan.nama as kegiatan',
                'kelompok_mahasiswa.is_koordinator',
            ])
            ->when($kegiatanId, fn($q) => $q->where('survey_lokasi.kegiatan_id', $kegiatanId))
            ->when($kelompokNo,  fn($q) => $q->where('survey_lokasi.kelompok', $kelompokNo))
            ->orderBy('survey_lokasi.kelompok')
            ->orderByRaw('kelompok_mahasiswa.is_koordinator DESC')
            ->orderBy('mahasiswa.nama')
            ->paginate(15)
            ->withQueryString();

        return view('peserta.index', compact(
            'kegiatanList', 'kelompokNumbers', 'peserta', 'kegiatanId', 'kelompokNo'
        ));
    }

    /**
     * Daftar DPL yang sudah ditugaskan ke kelompok (flat, paginated).
     */
    public function dpl(Request $request)
    {
        $kegiatanList = Kegiatan::orderByDesc('created_at')->get();

        $kegiatanId = $request->kegiatan_id;

        $dpl = DB::table('kelompok_dosen')
            ->join('pegawai',      'kelompok_dosen.pegawai_id',         '=', 'pegawai.id')
            ->join('survey_lokasi','kelompok_dosen.survey_lokasi_id',   '=', 'survey_lokasi.id')
            ->leftJoin('kegiatan', 'survey_lokasi.kegiatan_id', '=', 'kegiatan.id')
            ->leftJoin('desa',     'survey_lokasi.desa_id',     '=', 'desa.id')
            ->select([
                'pegawai.nama',
                'pegawai.nip',
                'pegawai.no_hp',
                'pegawai.email',
                'survey_lokasi.kelompok',
                'desa.nama as desa',
                'kegiatan.nama as kegiatan',
            ])
            ->when($kegiatanId, fn($q) => $q->where('survey_lokasi.kegiatan_id', $kegiatanId))
            ->orderBy('survey_lokasi.kelompok')
            ->orderBy('pegawai.nama')
            ->paginate(15)
            ->withQueryString();

        return view('dpl.index', compact('kegiatanList', 'dpl', 'kegiatanId'));
    }
}
