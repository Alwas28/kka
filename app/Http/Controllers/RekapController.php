<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function pendaftaran(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.mahasiswa-admin'), 403);

        // Rekap per kegiatan: jumlah peserta + breakdown status pendaftaran
        $perKegiatan = DB::table('mahasiswa_pendaftaran as mp')
            ->join('kegiatan as k', 'k.id', '=', 'mp.kegiatan_id')
            ->leftJoin('jenis_kka as jk', 'jk.id', '=', 'k.jenis_kka_id')
            ->select([
                'k.id as kegiatan_id',
                'k.nama as kegiatan_nama',
                'jk.nama as jenis_nama',
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN mp.status = 'submitted' THEN 1 ELSE 0 END) as sudah_submit"),
                DB::raw("SUM(CASE WHEN mp.status = 'draft' THEN 1 ELSE 0 END) as draft"),
                DB::raw("SUM(CASE WHEN (mp.status IS NULL OR mp.status NOT IN ('submitted','draft')) THEN 1 ELSE 0 END) as belum_isi"),
            ])
            ->groupBy('k.id', 'k.nama', 'jk.nama')
            ->orderByDesc('total')
            ->get();

        $totalMahasiswa = DB::table('mahasiswa')->count();
        $totalPendaftar = DB::table('mahasiswa_pendaftaran')->count();
        $totalSubmit    = DB::table('mahasiswa_pendaftaran')->where('status', 'submitted')->count();
        $totalDraft     = DB::table('mahasiswa_pendaftaran')->where('status', 'draft')->count();
        $totalBelum     = $totalMahasiswa - $totalPendaftar;

        $levels = MahasiswaLevel::withCount('mahasiswa')->orderBy('id')->get();

        return view('rekap.pendaftaran', compact(
            'perKegiatan',
            'totalMahasiswa', 'totalPendaftar', 'totalSubmit', 'totalDraft', 'totalBelum',
            'levels'
        ));
    }
}
