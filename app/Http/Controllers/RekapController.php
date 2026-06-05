<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaLevel;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RekapController extends Controller
{
    // Semua kolom yang tersedia untuk tabel & export
    private const ALL_COLUMNS = [
        'no'                 => 'No',
        'nim'                => 'NIM',
        'nama'               => 'Nama Mahasiswa',
        'email'              => 'Email',
        'prodi'              => 'Program Studi',
        'level'              => 'Level',
        'jenis_kelamin'      => 'Jenis Kelamin',
        'tempat_lahir'       => 'Tempat Lahir',
        'tanggal_lahir'      => 'Tanggal Lahir',
        'no_hp'              => 'No. HP',
        'golongan_darah'     => 'Gol. Darah',
        'alamat'             => 'Alamat',
        'semester'           => 'Semester',
        'sks_ditempuh'       => 'SKS Ditempuh',
        'ipk'                => 'IPK',
        'ukuran_baju'        => 'Ukuran Baju',
        'penyakit_diderita'  => 'Penyakit Diderita',
        'sedang_hamil'       => 'Sedang Hamil',
        'catatan_kesehatan'  => 'Catatan Kesehatan',
        'status_pendaftaran' => 'Status Pendaftaran',
        'kelompok'           => 'No. Kelompok',
    ];

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

        $kegiatan = $this->getKegiatan($kegiatanId);
        abort_if(!$kegiatan, 404);

        $mahasiswaList = $this->buildMahasiswaQuery($kegiatanId, $request)
            ->select($this->identitasSelect())
            ->orderBy('m.nama')
            ->paginate(25)
            ->withQueryString();

        $kelompokMap = $this->getKelompokMap($kegiatanId);

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

        $kegiatan = $this->getKegiatan($kegiatanId);
        abort_if(!$kegiatan, 404);

        $allColumns = self::ALL_COLUMNS;

        $selectedColumns = collect($request->input('kolom', array_keys($allColumns)))
            ->filter(fn($c) => isset($allColumns[$c]))
            ->values()
            ->toArray();

        if (empty($selectedColumns)) {
            $selectedColumns = array_keys($allColumns);
        }

        $rows = $this->buildMahasiswaQuery($kegiatanId, $request)
            ->select($this->identitasSelect())
            ->orderBy('m.nama')
            ->get();

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

        $html  = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>';
        $html .= '<table border="1" cellspacing="0" cellpadding="0" style="font-family:Arial,sans-serif;font-size:11px;border-collapse:collapse;">';
        $html .= '<tr><td colspan="' . $colspan . '" style="font-weight:bold;font-size:13px;background:#8B0000;color:#fff;padding:10px 14px;">'
               . htmlspecialchars("Rekap Pendaftaran KKA \u{2014} {$kegiatan->nama}") . '</td></tr>';
        $html .= '<tr><td colspan="' . $colspan . '" style="font-size:10px;color:#555;padding:5px 14px;background:#fef2f2;">'
               . htmlspecialchars("Jenis KKA: {$kegiatan->jenis_nama}  |  Periode: {$kegiatan->periode_nama}  |  Diekspor: {$exportedAt}  |  Total: {$rows->count()} mahasiswa")
               . '</td></tr>';
        $html .= '<tr><td colspan="' . $colspan . '" style="padding:3px;"></td></tr>';

        // Header
        $html .= '<tr>';
        foreach ($selectedColumns as $col) {
            $html .= '<th style="background:#8B0000;color:#fff;font-weight:bold;padding:7px 10px;text-align:left;border:1px solid #a00;">'
                   . htmlspecialchars($allColumns[$col]) . '</th>';
        }
        $html .= '</tr>';

        // Data
        foreach ($rows as $i => $mhs) {
            $bg    = $i % 2 === 0 ? '#ffffff' : '#fafafa';
            $html .= '<tr style="background:' . $bg . ';">';
            foreach ($selectedColumns as $col) {
                $val = $this->resolveColumnValue($col, $mhs, $kelompokMap, $i);
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

    public function grafik(Request $request, $kegiatanId)
    {
        abort_unless(auth()->user()->hasAccess('lihat.mahasiswa-admin'), 403);

        $kegiatan = $this->getKegiatan($kegiatanId);
        abort_if(!$kegiatan, 404);

        $rows = DB::table('mahasiswa_pendaftaran as mp')
            ->join('mahasiswa as m', 'm.id', '=', 'mp.mahasiswa_id')
            ->leftJoin('program_studi as ps', 'ps.id', '=', 'm.program_studi_id')
            ->where('mp.kegiatan_id', $kegiatanId)
            ->select([
                'mp.jenis_kelamin',
                'mp.ukuran_baju',
                'mp.semester',
                'mp.sks_ditempuh',
                'mp.ipk',
                'mp.penyakit_diderita',
                'mp.sedang_hamil',
                'mp.catatan_kesehatan',
                'ps.nama as prodi_nama',
            ])
            ->get();

        $total = $rows->count();

        // Jenis Kelamin
        $jkCounts     = $rows->groupBy('jenis_kelamin')->map->count();
        $jenisKelamin = [
            'labels' => ['Laki-laki', 'Perempuan'],
            'data'   => [$jkCounts->get('L', 0), $jkCounts->get('P', 0)],
        ];

        // Ukuran Baju (urutan standar)
        $bajuCounts = $rows->groupBy('ukuran_baju')->map->count();
        $sizeOrder  = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
        $ukuranBaju = [
            'labels' => $sizeOrder,
            'data'   => array_map(fn($s) => $bajuCounts->get($s, 0), $sizeOrder),
        ];

        // Program Studi (diurutkan terbanyak)
        $prodiCounts = $rows->groupBy('prodi_nama')->map->count()->sortByDesc(fn($v) => $v);
        $prodiData   = [
            'labels' => $prodiCounts->keys()->map(fn($k) => $k ?? 'Tidak Diketahui')->values()->toArray(),
            'data'   => $prodiCounts->values()->toArray(),
        ];

        // Semester
        $smtCounts    = $rows->groupBy('semester')->map->count()->sortKeys();
        $semesterData = [
            'labels' => $smtCounts->keys()->map(fn($k) => "Smt {$k}")->values()->toArray(),
            'data'   => $smtCounts->values()->toArray(),
        ];

        // SKS — 5 rentang
        $sksGroups = ['< 100' => 0, '100–120' => 0, '121–140' => 0, '141–160' => 0, '> 160' => 0];
        foreach ($rows as $r) {
            $v = (int) $r->sks_ditempuh;
            if      ($v < 100) $sksGroups['< 100']++;
            elseif  ($v <= 120) $sksGroups['100–120']++;
            elseif  ($v <= 140) $sksGroups['121–140']++;
            elseif  ($v <= 160) $sksGroups['141–160']++;
            else                $sksGroups['> 160']++;
        }
        $sksData = ['labels' => array_keys($sksGroups), 'data' => array_values($sksGroups)];

        // IPK — 5 rentang
        $ipkGroups = ['< 2.50' => 0, '2.50–2.99' => 0, '3.00–3.49' => 0, '3.50–3.75' => 0, '3.76–4.00' => 0];
        foreach ($rows as $r) {
            $v = (float) $r->ipk;
            if      ($v < 2.50) $ipkGroups['< 2.50']++;
            elseif  ($v < 3.00) $ipkGroups['2.50–2.99']++;
            elseif  ($v < 3.50) $ipkGroups['3.00–3.49']++;
            elseif  ($v <= 3.75) $ipkGroups['3.50–3.75']++;
            else                 $ipkGroups['3.76–4.00']++;
        }
        $ipkData = ['labels' => array_keys($ipkGroups), 'data' => array_values($ipkGroups)];

        // Kesehatan — 2 kelas
        $sehat   = $rows->filter(fn($r) =>
            empty($r->penyakit_diderita) && !$r->sedang_hamil && empty($r->catatan_kesehatan)
        )->count();
        $kesehatanData = [
            'labels' => ['Sehat', 'Sehat dengan Catatan'],
            'data'   => [$sehat, $total - $sehat],
        ];

        return view('rekap.grafik', compact(
            'kegiatan', 'total',
            'jenisKelamin', 'ukuranBaju', 'prodiData',
            'semesterData', 'sksData', 'ipkData', 'kesehatanData'
        ));
    }

    // ── Private helpers ────────────────────────────────────────────────────────

    private function getKegiatan($kegiatanId)
    {
        return DB::table('kegiatan as k')
            ->leftJoin('jenis_kka as jk', 'jk.id', '=', 'k.jenis_kka_id')
            ->leftJoin('periode as per', 'per.id', '=', 'k.periode_id')
            ->where('k.id', $kegiatanId)
            ->select(['k.id', 'k.nama', 'jk.nama as jenis_nama', 'per.nama as periode_nama'])
            ->first();
    }

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

    private function identitasSelect(): array
    {
        return [
            'm.id as mahasiswa_id',
            'm.nim',
            'm.nama',
            'm.email',
            'ps.nama as prodi_nama',
            'ml.nama as level_nama',
            'mp.jenis_kelamin',
            'mp.tempat_lahir',
            'mp.tanggal_lahir',
            'mp.no_hp',
            'mp.golongan_darah',
            'mp.alamat',
            'mp.semester',
            'mp.sks_ditempuh',
            'mp.ipk',
            'mp.ukuran_baju',
            'mp.penyakit_diderita',
            'mp.sedang_hamil',
            'mp.catatan_kesehatan',
            'mp.status as pendaftaran_status',
        ];
    }

    private function getKelompokMap($kegiatanId)
    {
        return DB::table('kelompok_mahasiswa as km')
            ->join('survey_lokasi as sl', 'sl.id', '=', 'km.survey_lokasi_id')
            ->where('sl.kegiatan_id', $kegiatanId)
            ->select(['km.mahasiswa_id', 'sl.kelompok', 'sl.id as survey_lokasi_id'])
            ->get()
            ->keyBy('mahasiswa_id');
    }

    private function resolveColumnValue(string $col, object $mhs, $kelompokMap, int $i): string
    {
        return match ($col) {
            'no'                 => (string) ($i + 1),
            'nim'                => $mhs->nim ?? '-',
            'nama'               => $mhs->nama ?? '-',
            'email'              => $mhs->email ?? '-',
            'prodi'              => $mhs->prodi_nama ?? '-',
            'level'              => $mhs->level_nama ?? '-',
            'jenis_kelamin'      => match ($mhs->jenis_kelamin ?? '') {
                'L' => 'Laki-laki', 'P' => 'Perempuan', default => '-',
            },
            'tempat_lahir'       => $mhs->tempat_lahir ?? '-',
            'tanggal_lahir'      => $mhs->tanggal_lahir
                ? \Carbon\Carbon::parse($mhs->tanggal_lahir)->format('d/m/Y')
                : '-',
            'no_hp'              => $mhs->no_hp ?? '-',
            'golongan_darah'     => $mhs->golongan_darah ?? '-',
            'alamat'             => $mhs->alamat ?? '-',
            'semester'           => $mhs->semester !== null ? (string) $mhs->semester : '-',
            'sks_ditempuh'       => $mhs->sks_ditempuh !== null ? (string) $mhs->sks_ditempuh : '-',
            'ipk'                => $mhs->ipk !== null ? number_format((float) $mhs->ipk, 2) : '-',
            'ukuran_baju'        => $mhs->ukuran_baju ?? '-',
            'penyakit_diderita'  => $mhs->penyakit_diderita ?? '-',
            'sedang_hamil'       => match ($mhs->sedang_hamil ?? null) {
                1, true  => 'Ya',
                0, false => 'Tidak',
                default  => '-',
            },
            'catatan_kesehatan'  => $mhs->catatan_kesehatan ?? '-',
            'status_pendaftaran' => match ($mhs->pendaftaran_status ?? '') {
                'submitted' => 'Sudah Submit', 'draft' => 'Draft', default => 'Belum Mengisi',
            },
            'kelompok'           => $kelompokMap->has($mhs->mahasiswa_id)
                ? 'Kelompok ' . ($kelompokMap->get($mhs->mahasiswa_id)->kelompok ?? '-')
                : 'Belum Ada Kelompok',
            default              => '-',
        };
    }
}
