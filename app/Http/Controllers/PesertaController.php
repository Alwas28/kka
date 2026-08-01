<?php

namespace App\Http\Controllers;

use App\Models\JenisKka;
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
        abort_unless(auth()->user()->hasAccess('lihat.peserta'), 403);

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
        abort_unless(auth()->user()->hasAccess('lihat.dpl'), 403);

        $kegiatanList = Kegiatan::orderByDesc('created_at')->get();
        $jenisKkaList = JenisKka::orderBy('nama')->get();
        $tahunList    = DB::table('tahun')->orderByDesc('nama')->get();

        $kegiatanId = $request->kegiatan_id;
        $jenisKkaId = $request->jenis_kka_id;
        $tahunId    = $request->tahun_id;

        $dpl = $this->dplQuery($kegiatanId, $jenisKkaId, $tahunId)
            ->orderBy('survey_lokasi.kelompok')
            ->orderBy('pegawai.nama')
            ->paginate(15)
            ->withQueryString();

        return view('dpl.index', compact(
            'kegiatanList', 'jenisKkaList', 'tahunList',
            'dpl', 'kegiatanId', 'jenisKkaId', 'tahunId'
        ));
    }

    /**
     * Export daftar DPL ke Excel. Wajib pilih Jenis KKA & Tahun Pelaksanaan
     * terlebih dahulu (supaya export tidak menarik seluruh data tanpa konteks).
     */
    public function exportDpl(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.dpl'), 403);

        $kegiatanId = $request->kegiatan_id;
        $jenisKkaId = $request->jenis_kka_id;
        $tahunId    = $request->tahun_id;

        abort_unless($jenisKkaId && $tahunId, 422, 'Pilih Jenis KKA dan Tahun Pelaksanaan terlebih dahulu sebelum export.');

        $rows = $this->dplQuery($kegiatanId, $jenisKkaId, $tahunId)
            ->orderBy('survey_lokasi.kelompok')
            ->orderBy('pegawai.nama')
            ->get();

        $jenisNama = JenisKka::find($jenisKkaId)?->nama ?? '-';
        $tahunNama = DB::table('tahun')->where('id', $tahunId)->value('nama') ?? '-';

        $columns = ['No', 'Nama DPL', 'NIP', 'No. HP', 'Email', 'Kelompok', 'Lokasi', 'Kegiatan'];
        $colspan = count($columns);
        $filename = 'dpl-' . \Illuminate\Support\Str::slug($jenisNama . '-' . $tahunNama) . '-' . date('Ymd') . '.xls';
        $exportedAt = now()->format('d/m/Y H:i');

        $html  = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>';
        $html .= '<table border="1" cellspacing="0" cellpadding="0" style="font-family:Arial,sans-serif;font-size:11px;border-collapse:collapse;">';
        $html .= '<tr><td colspan="' . $colspan . '" style="font-weight:bold;font-size:13px;background:#8B0000;color:#fff;padding:10px 14px;">'
               . htmlspecialchars("Daftar Dosen Pembimbing Lapangan \u{2014} {$jenisNama} {$tahunNama}") . '</td></tr>';
        $html .= '<tr><td colspan="' . $colspan . '" style="font-size:10px;color:#555;padding:5px 14px;background:#fef2f2;">'
               . htmlspecialchars("Diekspor: {$exportedAt}  |  Total: {$rows->count()} penugasan DPL")
               . '</td></tr>';
        $html .= '<tr><td colspan="' . $colspan . '" style="padding:3px;"></td></tr>';

        $html .= '<tr>';
        foreach ($columns as $col) {
            $html .= '<th style="background:#8B0000;color:#fff;font-weight:bold;padding:7px 10px;text-align:left;border:1px solid #a00;">'
                   . htmlspecialchars($col) . '</th>';
        }
        $html .= '</tr>';

        foreach ($rows as $i => $d) {
            $bg = $i % 2 === 0 ? '#ffffff' : '#fafafa';
            $html .= '<tr style="background:' . $bg . ';">';
            foreach ([
                $i + 1, $d->nama, $d->nip ?? '-', $d->no_hp ?? '-', $d->email ?? '-',
                $d->kelompok, $d->desa ?? '-', $d->kegiatan ?? '-',
            ] as $val) {
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

    /** Query dasar daftar DPL yang ditugaskan, dengan filter opsional. */
    private function dplQuery($kegiatanId, $jenisKkaId, $tahunId)
    {
        return DB::table('kelompok_dosen')
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
            ->when($jenisKkaId, fn($q) => $q->where('kegiatan.jenis_kka_id', $jenisKkaId))
            ->when($tahunId, fn($q) => $q->where('kegiatan.tahun_id', $tahunId));
    }
}
