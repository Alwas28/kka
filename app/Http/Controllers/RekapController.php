<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaLevel;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function pendaftaran(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.mahasiswa-admin'), 403);

        $periodeId = $request->input('periode_id');

        // Stats: ikut filter periode jika aktif
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

        // Rekap per kegiatan
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

        $mahasiswaList = $query->select([
            'm.id as mahasiswa_id',
            'm.nim',
            'm.nama',
            'm.email',
            'ps.nama as prodi_nama',
            'ml.nama as level_nama',
            'mp.status as pendaftaran_status',
        ])->orderBy('m.nama')->paginate(25)->withQueryString();

        // mahasiswa_id → survey_lokasi_id (untuk link ke profil)
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
}
