<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DosenPembimbingController extends Controller
{
    private function getMyPegawai(): ?object
    {
        $user = Auth::user();

        return DB::table('pegawai')
            ->leftJoin('users', 'pegawai.user_id', '=', 'users.id')
            ->where(function ($q) use ($user) {
                $q->where('pegawai.user_id', $user->id)
                  ->orWhere('pegawai.email', $user->email);
            })
            ->select('pegawai.*', 'users.email as email_user')
            ->first();
    }

    // ── INDEX ─────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        abort_unless(Auth::user()->hasAccess('lihat.dosen-pembimbing'), 403);

        $pegawai = $this->getMyPegawai();

        if (!$pegawai) {
            return view('dpl.dosen.index', [
                'pegawai'         => null,
                'kegiatanList'    => collect(),
                'mahasiswaCounts' => collect(),
                'tahunList'       => collect(),
                'tahunId'         => null,
            ]);
        }

        $tahunList = DB::table('tahun')->orderByDesc('nama')->get();
        $tahunId   = $request->tahun_id;

        $kegiatanList = DB::table('kegiatan')
            ->join('survey_lokasi',  'kegiatan.id',          '=', 'survey_lokasi.kegiatan_id')
            ->join('kelompok_dosen', 'survey_lokasi.id',     '=', 'kelompok_dosen.survey_lokasi_id')
            ->where('kelompok_dosen.pegawai_id', $pegawai->id)
            ->when($tahunId, fn($q) => $q->where('kegiatan.tahun_id', $tahunId))
            ->select([
                'kegiatan.id',
                'kegiatan.nama',
                'kegiatan.kegiatan_mulai',
                'kegiatan.kegiatan_selesai',
                DB::raw('COUNT(DISTINCT survey_lokasi.id) as jumlah_kelompok'),
            ])
            ->groupBy('kegiatan.id', 'kegiatan.nama', 'kegiatan.kegiatan_mulai', 'kegiatan.kegiatan_selesai')
            ->orderByDesc('kegiatan.id')
            ->get();

        $mahasiswaCounts = DB::table('kelompok_dosen')
            ->join('survey_lokasi',      'kelompok_dosen.survey_lokasi_id', '=', 'survey_lokasi.id')
            ->join('kelompok_mahasiswa', 'survey_lokasi.id',               '=', 'kelompok_mahasiswa.survey_lokasi_id')
            ->where('kelompok_dosen.pegawai_id', $pegawai->id)
            ->when($tahunId, fn($q) => $q->where('survey_lokasi.kegiatan_id', function ($sub) use ($tahunId) {
                $sub->select('id')->from('kegiatan')->where('tahun_id', $tahunId);
            }))
            ->selectRaw('survey_lokasi.kegiatan_id, COUNT(DISTINCT kelompok_mahasiswa.mahasiswa_id) as total')
            ->groupBy('survey_lokasi.kegiatan_id')
            ->pluck('total', 'kegiatan_id');

        return view('dpl.dosen.index', compact(
            'pegawai', 'kegiatanList', 'mahasiswaCounts', 'tahunList', 'tahunId'
        ));
    }

    // ── DETAIL ────────────────────────────────────────────────────────────────
    public function detail(Request $request, $kegiatanId)
    {
        abort_unless(Auth::user()->hasAccess('lihat.dosen-pembimbing'), 403);

        $pegawai = $this->getMyPegawai();
        abort_if(!$pegawai, 403, 'Anda tidak terdaftar sebagai Dosen Pembimbing.');

        $kegiatan = DB::table('kegiatan')->where('id', $kegiatanId)->first();
        abort_if(!$kegiatan, 404);

        // Kelompok DPL ini
        $kelompokList = DB::table('kelompok_dosen')
            ->join('survey_lokasi', 'kelompok_dosen.survey_lokasi_id', '=', 'survey_lokasi.id')
            ->leftJoin('desa',      'survey_lokasi.desa_id',    '=', 'desa.id')
            ->leftJoin('kecamatan', 'desa.kecamatan_id',        '=', 'kecamatan.id')
            ->leftJoin('kabupaten', 'kecamatan.kabupaten_id',   '=', 'kabupaten.id')
            ->where('kelompok_dosen.pegawai_id', $pegawai->id)
            ->where('survey_lokasi.kegiatan_id', $kegiatanId)
            ->select([
                'survey_lokasi.id as survey_id',
                'survey_lokasi.kelompok',
                'desa.nama as desa',
                'kecamatan.nama as kecamatan',
                'kabupaten.nama as kabupaten',
            ])
            ->orderBy('survey_lokasi.kelompok')
            ->get();

        $surveyIds = $kelompokList->pluck('survey_id');

        // Peserta per kelompok
        $pesertaByKelompok = DB::table('kelompok_mahasiswa')
            ->join('mahasiswa',         'kelompok_mahasiswa.mahasiswa_id', '=', 'mahasiswa.id')
            ->leftJoin('program_studi', 'mahasiswa.program_studi_id',      '=', 'program_studi.id')
            ->whereIn('kelompok_mahasiswa.survey_lokasi_id', $surveyIds)
            ->select([
                'kelompok_mahasiswa.survey_lokasi_id',
                'kelompok_mahasiswa.is_koordinator',
                'mahasiswa.id as mahasiswa_id',
                'mahasiswa.nim',
                'mahasiswa.nama',
                'program_studi.nama as prodi',
            ])
            ->orderByRaw('kelompok_mahasiswa.is_koordinator DESC')
            ->orderBy('mahasiswa.nama')
            ->get()
            ->groupBy('survey_lokasi_id');

        // Logbook count per mahasiswa per kelompok
        $logbookPerMhs = DB::table('logbook')
            ->whereIn('survey_lokasi_id', $surveyIds)
            ->selectRaw('mahasiswa_id, survey_lokasi_id, COUNT(*) as total')
            ->groupBy('mahasiswa_id', 'survey_lokasi_id')
            ->get()
            ->groupBy('survey_lokasi_id')
            ->map(fn($rows) => $rows->pluck('total', 'mahasiswa_id'));

        // Logbook total per kelompok
        $logbookTotalByKel = DB::table('logbook')
            ->whereIn('survey_lokasi_id', $surveyIds)
            ->selectRaw('survey_lokasi_id, COUNT(*) as total')
            ->groupBy('survey_lokasi_id')
            ->pluck('total', 'survey_lokasi_id');

        // Laporan akhir per kelompok
        $laporanAkhirByKelompok = DB::table('laporan_akhir')
            ->join('mahasiswa',           'laporan_akhir.mahasiswa_id',        '=', 'mahasiswa.id')
            ->leftJoin('kegiatan_dokumen','laporan_akhir.kegiatan_dokumen_id', '=', 'kegiatan_dokumen.id')
            ->whereIn('laporan_akhir.survey_lokasi_id', $surveyIds)
            ->select([
                'laporan_akhir.id',
                'laporan_akhir.survey_lokasi_id',
                'laporan_akhir.file_path',
                'laporan_akhir.file_name',
                'laporan_akhir.file_size',
                'laporan_akhir.keterangan',
                'laporan_akhir.created_at as uploaded_at',
                'kegiatan_dokumen.nama as dokumen_nama',
                'mahasiswa.nama as koordinator_nama',
            ])
            ->get()
            ->groupBy('survey_lokasi_id');

        // Laporan individu per mahasiswa
        $laporanIndividuByMhs = DB::table('laporan_individu')
            ->leftJoin('kegiatan_dokumen', 'laporan_individu.kegiatan_dokumen_id', '=', 'kegiatan_dokumen.id')
            ->whereIn('laporan_individu.survey_lokasi_id', $surveyIds)
            ->select([
                'laporan_individu.mahasiswa_id',
                'laporan_individu.survey_lokasi_id',
                'laporan_individu.file_path',
                'laporan_individu.file_name',
                'kegiatan_dokumen.nama as dokumen_nama',
            ])
            ->get()
            ->groupBy(fn($r) => $r->survey_lokasi_id . '_' . $r->mahasiswa_id);

        // Komponen penilaian kegiatan ini
        $komponenPenilaian = DB::table('kegiatan_komponen_penilaian')
            ->where('kegiatan_id', $kegiatanId)
            ->orderBy('urutan')
            ->get();

        // Nilai per komponen: [survey_id][mahasiswa_id][komponen_id] = nilai
        $nilaiKomponenRaw = DB::table('nilai_komponen')
            ->whereIn('survey_lokasi_id', $surveyIds)
            ->select([
                'mahasiswa_id',
                'survey_lokasi_id',
                'kegiatan_komponen_penilaian_id as komponen_id',
                'nilai',
            ])
            ->get();

        $nilaiKomponenBySurvey = [];
        foreach ($nilaiKomponenRaw as $nk) {
            $nilaiKomponenBySurvey[$nk->survey_lokasi_id][$nk->mahasiswa_id][$nk->komponen_id] = $nk->nilai;
        }

        // Nilai akhir + catatan
        $nilaiAkhirByKelompok = DB::table('nilai_mahasiswa')
            ->whereIn('survey_lokasi_id', $surveyIds)
            ->select(['mahasiswa_id', 'survey_lokasi_id', 'nilai_akhir', 'catatan'])
            ->get()
            ->groupBy('survey_lokasi_id')
            ->map(fn($rows) => $rows->keyBy('mahasiswa_id'));

        // Grade table
        $gradeTable = DB::table('kegiatan_grade')
            ->where('kegiatan_id', $kegiatanId)
            ->orderByDesc('nilai_min')
            ->get();

        [$nilaiTerbuka, $tahapanPelaporan] = $this->cekPeriodePenilaian($kegiatanId);

        return view('dpl.dosen.detail', compact(
            'pegawai', 'kegiatan',
            'kelompokList', 'pesertaByKelompok',
            'logbookPerMhs', 'logbookTotalByKel',
            'laporanAkhirByKelompok', 'laporanIndividuByMhs',
            'komponenPenilaian',
            'nilaiKomponenBySurvey', 'nilaiAkhirByKelompok',
            'gradeTable',
            'nilaiTerbuka', 'tahapanPelaporan'
        ));
    }

    // ── HELPER: CEK PERIODE PENILAIAN ─────────────────────────────────────────
    private function cekPeriodePenilaian(int $kegiatanId): array
    {
        $tahapan = DB::table('kegiatan_tahapan')
            ->where('kegiatan_id', $kegiatanId)
            ->where('nama', 'pelaporan')
            ->first();

        $terbuka = true;
        if ($tahapan && $tahapan->selesai !== null) {
            $terbuka = now()->lte(\Carbon\Carbon::parse($tahapan->selesai)->endOfDay());
        }

        return [$terbuka, $tahapan];
    }

    // ── SAVE NILAI ────────────────────────────────────────────────────────────
    public function saveNilai(Request $request, $kegiatanId)
    {
        abort_unless(Auth::user()->hasAccess('lihat.dosen-pembimbing'), 403);

        $pegawai = $this->getMyPegawai();
        abort_if(!$pegawai, 403);

        [$nilaiTerbuka] = $this->cekPeriodePenilaian((int) $kegiatanId);
        abort_unless($nilaiTerbuka, 403, 'Periode penilaian telah berakhir.');

        $kegiatanNama = DB::table('kegiatan')->where('id', $kegiatanId)->value('nama') ?? 'KKA';

        $surveyIds = DB::table('kelompok_dosen')
            ->join('survey_lokasi', 'kelompok_dosen.survey_lokasi_id', '=', 'survey_lokasi.id')
            ->where('kelompok_dosen.pegawai_id', $pegawai->id)
            ->where('survey_lokasi.kegiatan_id', $kegiatanId)
            ->pluck('survey_lokasi.id');

        // Komponen valid: id → persentase
        $komponenList = DB::table('kegiatan_komponen_penilaian')
            ->where('kegiatan_id', $kegiatanId)
            ->orderBy('urutan')
            ->get()
            ->keyBy('id');

        $request->validate([
            'nilai'                    => 'required|array',
            'nilai.*.mahasiswa_id'     => 'required|integer',
            'nilai.*.survey_lokasi_id' => 'required|integer',
            'nilai.*.catatan'          => 'nullable|string|max:500',
            'nilai.*.komponen'         => 'nullable|array',
            'nilai.*.komponen.*'       => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($request->nilai as $item) {
            $surveyId = (int) $item['survey_lokasi_id'];
            $mhsId    = (int) $item['mahasiswa_id'];

            if (!$surveyIds->contains($surveyId)) continue;

            $kompoValues     = $item['komponen'] ?? [];
            $nilaiAkhir      = null;
            $totalPersentase = 0;

            foreach ($komponenList as $kompoId => $kompo) {
                $nilaiKompo = isset($kompoValues[$kompoId]) && $kompoValues[$kompoId] !== ''
                    ? (float) $kompoValues[$kompoId]
                    : null;

                DB::table('nilai_komponen')->updateOrInsert(
                    [
                        'mahasiswa_id'                   => $mhsId,
                        'survey_lokasi_id'               => $surveyId,
                        'kegiatan_komponen_penilaian_id' => $kompoId,
                    ],
                    [
                        'nilai'      => $nilaiKompo,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );

                if ($nilaiKompo !== null) {
                    $nilaiAkhir      = ($nilaiAkhir ?? 0) + $nilaiKompo * ($kompo->persentase / 100);
                    $totalPersentase += $kompo->persentase;
                }
            }

            // Jika komponen yang diisi tidak genap 100%, normalisasi proporsional
            if ($nilaiAkhir !== null && $totalPersentase > 0 && $totalPersentase < 100) {
                $nilaiAkhir = $nilaiAkhir / $totalPersentase * 100;
            }

            // Cek apakah ini penilaian pertama (belum ada nilai_akhir sebelumnya)
            $sudahDinilai = DB::table('nilai_mahasiswa')
                ->where('mahasiswa_id', $mhsId)
                ->where('survey_lokasi_id', $surveyId)
                ->whereNotNull('nilai_akhir')
                ->exists();

            DB::table('nilai_mahasiswa')->updateOrInsert(
                [
                    'mahasiswa_id'     => $mhsId,
                    'survey_lokasi_id' => $surveyId,
                ],
                [
                    'pegawai_id'  => $pegawai->id,
                    'nilai_akhir' => $nilaiAkhir,
                    'catatan'     => $item['catatan'] ?? null,
                    'updated_at'  => now(),
                    'created_at'  => now(),
                ]
            );

            // Jika nilai_akhir baru saja diinput untuk pertama kali
            if ($nilaiAkhir !== null && !$sudahDinilai) {
                // Naikkan level mahasiswa ke "Selesai" (id=7)
                DB::table('mahasiswa')
                    ->where('id', $mhsId)
                    ->where('mahasiswa_level_id', '<', 7)
                    ->update(['mahasiswa_level_id' => 7]);

                // Kirim notifikasi ke mahasiswa
                DB::table('mahasiswa_notifikasi')->insert([
                    'mahasiswa_id' => $mhsId,
                    'judul'        => 'Nilai KKA Tersedia',
                    'pesan'        => 'Nilai kegiatan ' . $kegiatanNama . ' Anda telah diinputkan oleh DPL. Nilai akhir: ' . number_format($nilaiAkhir, 2) . '.',
                    'ikon'         => 'fa-star',
                    'warna'        => '#059669',
                    'url'          => null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }
        }

        return back()->with('success', 'Nilai berhasil disimpan.');
    }
}
