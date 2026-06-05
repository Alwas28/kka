<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaLevel;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RekapController extends Controller
{
    public function pendaftaran(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.mahasiswa-admin'), 403);

        $periodeId = $request->input('periode_id');

        $statsBase = DB::table('mahasiswa_pendaftaran as mp')
            ->join('kegiatan as k', 'k.id', '=', 'mp.kegiatan_id');
        if ($periodeId) {
            $statsBase->where('k.periode_id', $periodeId);
        }

        $totalMahasiswa = DB::table('mahasiswa')->count();
        $totalPendaftar = (clone $statsBase)->count('mp.id');
        $totalSubmit    = (clone $statsBase)->where('mp.status', 'submitted')->count('mp.id');
        $totalDraft     = (clone $statsBase)->where('mp.status', 'draft')->count('mp.id');
        $totalBelum     = $totalMahasiswa - (clone $statsBase)->distinct()->count('mp.mahasiswa_id');

        $perKegiatan = DB::table('mahasiswa_pendaftaran as mp')
            ->join('kegiatan as k', 'k.id', '=', 'mp.kegiatan_id')
            ->leftJoin('jenis_kka as jk', 'jk.id', '=', 'k.jenis_kka_id')
            ->leftJoin('periode as per', 'per.id', '=', 'k.periode_id')
            ->when($periodeId, fn($q) => $q->where('k.periode_id', $periodeId))
            ->select([
                'k.id as kegiatan_id',
                'k.nama as kegiatan_nama',
                'jk.nama as jenis_nama',
                'per.nama as periode_nama',
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN mp.status = 'submitted' THEN 1 ELSE 0 END) as sudah_submit"),
                DB::raw("SUM(CASE WHEN mp.status = 'draft' THEN 1 ELSE 0 END) as draft"),
                DB::raw("SUM(CASE WHEN (mp.status IS NULL OR mp.status NOT IN ('submitted','draft')) THEN 1 ELSE 0 END) as belum_isi"),
            ])
            ->groupBy('k.id', 'k.nama', 'jk.nama', 'per.nama')
            ->orderByDesc('total')
            ->get();

        $levels      = MahasiswaLevel::withCount('mahasiswa')->orderBy('id')->get();
        $periodeList = Periode::orderBy('nama')->get();

        return view('rekap.pendaftaran', compact(
            'perKegiatan', 'periodeList',
            'totalMahasiswa', 'totalPendaftar', 'totalSubmit', 'totalDraft', 'totalBelum',
            'levels'
        ));
    }

    public function detail(Request $request, $kegiatanId)
    {
        abort_unless(auth()->user()->hasAccess('lihat.mahasiswa-admin'), 403);

        $kegiatan = DB::table('kegiatan as k')
            ->leftJoin('jenis_kka as jk', 'jk.id', '=', 'k.jenis_kka_id')
            ->leftJoin('periode as per', 'per.id', '=', 'k.periode_id')
            ->where('k.id', $kegiatanId)
            ->select(['k.id', 'k.nama', 'jk.nama as jenis_nama', 'per.nama as periode_nama'])
            ->first();

        abort_if(!$kegiatan, 404);

        $query = $this->buildMahasiswaQuery($kegiatanId, $request);

        $mahasiswaList = $query->select([
            'm.id as mahasiswa_id',
            'm.nim',
            'm.nama',
            'm.email',
            'ps.nama as prodi_nama',
            'ml.nama as level_nama',
            'mp.status as pendaftaran_status',
        ])->orderBy('m.nama')->paginate(25)->withQueryString();

        $kelompokMap = DB::table('kelompok_mahasiswa as km')
            ->join('survey_lokasi as sl', 'sl.id', '=', 'km.survey_lokasi_id')
            ->where('sl.kegiatan_id', $kegiatanId)
            ->pluck('km.survey_lokasi_id', 'km.mahasiswa_id');

        $statTotal  = DB::table('mahasiswa_pendaftaran')->where('kegiatan_id', $kegiatanId)->count();
        $statSubmit = DB::table('mahasiswa_pendaftaran')->where('kegiatan_id', $kegiatanId)->where('status', 'submitted')->count();
        $statDraft  = DB::table('mahasiswa_pendaftaran')->where('kegiatan_id', $kegiatanId)->where('status', 'draft')->count();
        $statBelum  = $statTotal - $statSubmit - $statDraft;

        return view('rekap.detail', compact(
            'kegiatan', 'mahasiswaList', 'kelompokMap',
            'statTotal', 'statSubmit', 'statDraft', 'statBelum'
        ));
    }

    public function export(Request $request, $kegiatanId)
    {
        abort_unless(auth()->user()->hasAccess('lihat.mahasiswa-admin'), 403);

        $kegiatan = DB::table('kegiatan as k')
            ->leftJoin('jenis_kka as jk', 'jk.id', '=', 'k.jenis_kka_id')
            ->leftJoin('periode as per', 'per.id', '=', 'k.periode_id')
            ->where('k.id', $kegiatanId)
            ->select(['k.id', 'k.nama', 'jk.nama as jenis_nama', 'per.nama as periode_nama'])
            ->first();

        abort_if(!$kegiatan, 404);

        $allColumns = [
            'no'                 => 'No',
            'nim'                => 'NIM',
            'nama'               => 'Nama Mahasiswa',
            'email'              => 'Email',
            'prodi'              => 'Program Studi',
            'level'              => 'Level',
            'status_pendaftaran' => 'Status Pendaftaran',
            'kelompok'           => 'No. Kelompok',
        ];

        $selectedColumns = collect($request->input('kolom', array_keys($allColumns)))
            ->filter(fn($c) => isset($allColumns[$c]))
            ->values()
            ->toArray();

        if (empty($selectedColumns)) {
            $selectedColumns = array_keys($allColumns);
        }

        $rows = $this->buildMahasiswaQuery($kegiatanId, $request)
            ->select([
                'm.id as mahasiswa_id',
                'm.nim',
                'm.nama',
                'm.email',
                'ps.nama as prodi_nama',
                'ml.nama as level_nama',
                'mp.status as pendaftaran_status',
            ])->orderBy('m.nama')->get();

        $kelompokMap = collect();
        if (in_array('kelompok', $selectedColumns)) {
            $kelompokMap = DB::table('kelompok_mahasiswa as km')
                ->join('survey_lokasi as sl', 'sl.id', '=', 'km.survey_lokasi_id')
                ->where('sl.kegiatan_id', $kegiatanId)
                ->select(['km.mahasiswa_id', 'sl.kelompok'])
                ->get()
                ->keyBy('mahasiswa_id');
        }

        $filename   = 'rekap-' . Str::slug($kegiatan->nama) . '-' . date('Ymd') . '.xls';
        $colspan    = count($selectedColumns);
        $exportedAt = now()->format('d/m/Y H:i');

        $lines   = [];
        $lines[] = '<!DOCTYPE html>';
        $lines[] = '<html><head><meta charset="UTF-8"></head><body>';
        $lines[] = '<table border="1" cellspacing="0" cellpadding="0" style="font-family:Arial,sans-serif;font-size:11px;border-collapse:collapse;">';

        $lines[] = '<tr>'
            . '<td colspan="' . $colspan . '" style="font-weight:bold;font-size:13px;background:#8B0000;color:#fff;padding:10px 14px;">'
            . htmlspecialchars("Rekap Pendaftaran KKA \u{2014} {$kegiatan->nama}")
            . '</td></tr>';

        $lines[] = '<tr>'
            . '<td colspan="' . $colspan . '" style="font-size:10px;color:#555;padding:5px 14px;background:#fef2f2;">'
            . htmlspecialchars("Jenis KKA: {$kegiatan->jenis_nama}  |  Periode: {$kegiatan->periode_nama}  |  Diekspor: {$exportedAt}  |  Total: {$rows->count()} mahasiswa")
            . '</td></tr>';

        $lines[] = '<tr><td colspan="' . $colspan . '" style="padding:4px;"></td></tr>';

        // Header
        $headerCells = array_map(
            fn($col) => '<th style="background:#8B0000;color:#fff;font-weight:bold;padding:7px 10px;text-align:left;border:1px solid #a00;">'
                . htmlspecialchars($allColumns[$col]) . '</th>',
            $selectedColumns
        );
        $lines[] = '<tr>' . implode('', $headerCells) . '</tr>';

        // Data rows
        foreach ($rows as $i => $mhs) {
            $bg    = $i % 2 === 0 ? '#ffffff' : '#fafafa';
            $cells = array_map(function ($col) use ($mhs, $kelompokMap, $i) {
                $val = match ($col) {
                    'no'                 => $i + 1,
                    'nim'                => $mhs->nim,
                    'nama'               => $mhs->nama,
                    'email'              => $mhs->email,
                    'prodi'              => $mhs->prodi_nama ?? '-',
                    'level'              => $mhs->level_nama ?? '-',
                    'status_pendaftaran' => match ($mhs->pendaftaran_status) {
                        'submitted' => 'Sudah Submit',
                        'draft'     => 'Draft',
                        default     => 'Belum Mengisi',
                    },
                    'kelompok' => $kelompokMap->has($mhs->mahasiswa_id)
                        ? 'Kelompok ' . ($kelompokMap->get($mhs->mahasiswa_id)->kelompok ?? '-')
                        : 'Belum Ada Kelompok',
                    default => '-',
                };
                return '<td style="padding:5px 8px;border:1px solid #ddd;">' . htmlspecialchars((string) $val) . '</td>';
            }, $selectedColumns);

            $lines[] = '<tr style="background:' . $bg . ';">' . implode('', $cells) . '</tr>';
        }

        $lines[] = '</table></body></html>';

        return response(implode("\n", $lines), 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }

    // Query builder bersama untuk detail & export
    private function buildMahasiswaQuery($kegiatanId, Request $request)
    {
        $query = DB::table('mahasiswa_pendaftaran as mp')
            ->join('mahasiswa as m', 'm.id', '=', 'mp.mahasiswa_id')
            ->leftJoin('program_studi as ps', 'ps.id', '=', 'm.program_studi_id')
            ->leftJoin('mahasiswa_level as ml', 'ml.id', '=', 'm.mahasiswa_level_id')
            ->where('mp.kegiatan_id', $kegiatanId);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($b) use ($q) {
                $b->where('m.nama', 'like', "%{$q}%")
                  ->orWhere('m.nim', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'submitted' => $query->where('mp.status', 'submitted'),
                'draft'     => $query->where('mp.status', 'draft'),
                'belum'     => $query->where(function ($b) {
                    $b->whereNull('mp.status')
                      ->orWhereNotIn('mp.status', ['submitted', 'draft']);
                }),
                default     => null,
            };
        }

        return $query;
    }
}
