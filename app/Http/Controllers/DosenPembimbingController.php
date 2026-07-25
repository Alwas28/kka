<?php

namespace App\Http\Controllers;

use App\Models\SurveyLokasi;
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

    /** Pastikan pegawai ini adalah DPL yang ditugaskan ke kelompok (survey_lokasi) tsb. */
    private function isMyKelompok(int $pegawaiId, int $surveyLokasiId): bool
    {
        return DB::table('kelompok_dosen')
            ->where('pegawai_id', $pegawaiId)
            ->where('survey_lokasi_id', $surveyLokasiId)
            ->exists();
    }

    // ── INDEX ─────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        abort_unless(Auth::user()->hasAccess('lihat.dosen-pembimbing'), 403);

        $pegawai = $this->getMyPegawai();

        if (!$pegawai) {
            return view('dpl.dosen.index', [
                'pegawai'      => null,
                'tahunList'    => collect(),
                'jenisKkaList' => collect(),
                'tahunId'      => null,
                'jenisKkaId'   => null,
                'kelompokList' => collect(),
            ]);
        }

        $tahunList    = DB::table('tahun')->orderByDesc('nama')->get();
        $jenisKkaList = DB::table('jenis_kka')->orderBy('nama')->get();
        $tahunId      = $request->tahun_id;
        $jenisKkaId   = $request->jenis_kka_id;

        $kelompokList = collect();

        if ($tahunId && $jenisKkaId) {
            $kelompokList = DB::table('kelompok_dosen as kd')
                ->join('survey_lokasi as sl', 'kd.survey_lokasi_id', '=', 'sl.id')
                ->join('kegiatan as k',       'sl.kegiatan_id',      '=', 'k.id')
                ->leftJoin('desa',      'sl.desa_id',          '=', 'desa.id')
                ->leftJoin('kecamatan', 'desa.kecamatan_id',   '=', 'kecamatan.id')
                ->where('kd.pegawai_id', $pegawai->id)
                ->where('k.tahun_id', $tahunId)
                ->where('k.jenis_kka_id', $jenisKkaId)
                ->whereNotNull('sl.kelompok')
                ->select([
                    'sl.id as survey_id',
                    'sl.kelompok',
                    'desa.nama as desa',
                    'kecamatan.nama as kecamatan',
                    'k.nama as kegiatan_nama',
                ])
                ->orderBy('sl.kelompok')
                ->get();

            $surveyIds = $kelompokList->pluck('survey_id');

            $jumlahPeserta = DB::table('kelompok_mahasiswa')
                ->whereIn('survey_lokasi_id', $surveyIds)
                ->selectRaw('survey_lokasi_id, COUNT(*) as total')
                ->groupBy('survey_lokasi_id')
                ->pluck('total', 'survey_lokasi_id');

            $jumlahDinilai = DB::table('nilai_mahasiswa')
                ->whereIn('survey_lokasi_id', $surveyIds)
                ->whereNotNull('nilai_akhir')
                ->selectRaw('survey_lokasi_id, COUNT(*) as total')
                ->groupBy('survey_lokasi_id')
                ->pluck('total', 'survey_lokasi_id');

            $kelompokList = $kelompokList->map(function ($k) use ($jumlahPeserta, $jumlahDinilai) {
                $k->jumlah_peserta = $jumlahPeserta->get($k->survey_id, 0);
                $k->jumlah_dinilai = $jumlahDinilai->get($k->survey_id, 0);
                return $k;
            });
        }

        return view('dpl.dosen.index', compact(
            'pegawai', 'tahunList', 'jenisKkaList', 'tahunId', 'jenisKkaId', 'kelompokList'
        ));
    }

    // ── DETAIL (per kelompok) ────────────────────────────────────────────────
    public function detail(SurveyLokasi $survey)
    {
        abort_unless(Auth::user()->hasAccess('lihat.dosen-pembimbing'), 403);

        $pegawai = $this->getMyPegawai();
        abort_if(!$pegawai, 403, 'Anda tidak terdaftar sebagai Dosen Pembimbing.');
        abort_unless($this->isMyKelompok($pegawai->id, $survey->id), 403, 'Anda bukan Dosen Pembimbing untuk kelompok ini.');

        $survey->load(['desa.kecamatan.kabupaten.provinsi', 'kegiatan']);
        $kegiatan = $survey->kegiatan;
        abort_if(!$kegiatan, 404);

        // ── Peserta ──────────────────────────────────────────────
        $peserta = DB::table('kelompok_mahasiswa as km')
            ->join('mahasiswa as m',     'km.mahasiswa_id',      '=', 'm.id')
            ->leftJoin('program_studi as ps', 'm.program_studi_id', '=', 'ps.id')
            ->where('km.survey_lokasi_id', $survey->id)
            ->select([
                'm.id as mahasiswa_id', 'm.nim', 'm.nama',
                'ps.nama as prodi', 'km.is_koordinator',
            ])
            ->orderByRaw('km.is_koordinator DESC')
            ->orderBy('m.nama')
            ->get();

        $logbookPerMhs = DB::table('logbook')
            ->where('survey_lokasi_id', $survey->id)
            ->selectRaw('mahasiswa_id, COUNT(*) as total')
            ->groupBy('mahasiswa_id')
            ->pluck('total', 'mahasiswa_id');

        $laporanIndividuByMhs = DB::table('laporan_individu')
            ->leftJoin('kegiatan_dokumen', 'laporan_individu.kegiatan_dokumen_id', '=', 'kegiatan_dokumen.id')
            ->where('laporan_individu.survey_lokasi_id', $survey->id)
            ->select([
                'laporan_individu.mahasiswa_id',
                'laporan_individu.file_path',
                'laporan_individu.file_name',
                'kegiatan_dokumen.nama as dokumen_nama',
            ])
            ->get()
            ->groupBy('mahasiswa_id');

        $laporanAkhir = DB::table('laporan_akhir')
            ->join('mahasiswa', 'laporan_akhir.mahasiswa_id', '=', 'mahasiswa.id')
            ->leftJoin('kegiatan_dokumen', 'laporan_akhir.kegiatan_dokumen_id', '=', 'kegiatan_dokumen.id')
            ->where('laporan_akhir.survey_lokasi_id', $survey->id)
            ->select([
                'laporan_akhir.id',
                'laporan_akhir.file_path',
                'laporan_akhir.file_name',
                'laporan_akhir.file_size',
                'laporan_akhir.keterangan',
                'laporan_akhir.created_at as uploaded_at',
                'kegiatan_dokumen.nama as dokumen_nama',
                'mahasiswa.nama as koordinator_nama',
            ])
            ->get();

        // ── Nilai ────────────────────────────────────────────────
        $komponenPenilaian = DB::table('kegiatan_komponen_penilaian')
            ->where('kegiatan_id', $kegiatan->id)
            ->orderBy('urutan')
            ->get();

        $nilaiKomponenByMhs = [];
        foreach (DB::table('nilai_komponen')
            ->where('survey_lokasi_id', $survey->id)
            ->select(['mahasiswa_id', 'kegiatan_komponen_penilaian_id as komponen_id', 'nilai'])
            ->get() as $nk) {
            $nilaiKomponenByMhs[$nk->mahasiswa_id][$nk->komponen_id] = $nk->nilai;
        }

        $nilaiAkhirByMhs = DB::table('nilai_mahasiswa')
            ->where('survey_lokasi_id', $survey->id)
            ->select(['mahasiswa_id', 'nilai_akhir', 'catatan'])
            ->get()
            ->keyBy('mahasiswa_id');

        $gradeTable = DB::table('kegiatan_grade')
            ->where('kegiatan_id', $kegiatan->id)
            ->orderByDesc('nilai_min')
            ->get();

        [$nilaiTerbuka, $tahapanPelaporan] = $this->cekPeriodePenilaian($kegiatan->id);

        return view('dpl.dosen.detail', compact(
            'pegawai', 'survey', 'kegiatan', 'peserta',
            'logbookPerMhs', 'laporanIndividuByMhs', 'laporanAkhir',
            'komponenPenilaian', 'nilaiKomponenByMhs', 'nilaiAkhirByMhs', 'gradeTable',
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

    // ── SAVE NILAI (per kelompok) ────────────────────────────────────────────
    public function saveNilai(Request $request, SurveyLokasi $survey)
    {
        abort_unless(Auth::user()->hasAccess('lihat.dosen-pembimbing'), 403);

        $pegawai = $this->getMyPegawai();
        abort_if(!$pegawai, 403);
        abort_unless($this->isMyKelompok($pegawai->id, $survey->id), 403);

        $kegiatanId = $survey->kegiatan_id;
        abort_if(!$kegiatanId, 404);

        [$nilaiTerbuka] = $this->cekPeriodePenilaian((int) $kegiatanId);
        abort_unless($nilaiTerbuka, 403, 'Periode penilaian telah berakhir.');

        $kegiatanNama = DB::table('kegiatan')->where('id', $kegiatanId)->value('nama') ?? 'KKA';

        $mahasiswaIds = DB::table('kelompok_mahasiswa')
            ->where('survey_lokasi_id', $survey->id)
            ->pluck('mahasiswa_id');

        $komponenList = DB::table('kegiatan_komponen_penilaian')
            ->where('kegiatan_id', $kegiatanId)
            ->orderBy('urutan')
            ->get()
            ->keyBy('id');

        $request->validate([
            'nilai'                => 'required|array',
            'nilai.*.mahasiswa_id' => 'required|integer',
            'nilai.*.catatan'      => 'nullable|string|max:500',
            'nilai.*.komponen'     => 'nullable|array',
            'nilai.*.komponen.*'   => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($request->nilai as $item) {
            $mhsId = (int) $item['mahasiswa_id'];

            if (!$mahasiswaIds->contains($mhsId)) continue;

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
                        'survey_lokasi_id'               => $survey->id,
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
                ->where('survey_lokasi_id', $survey->id)
                ->whereNotNull('nilai_akhir')
                ->exists();

            DB::table('nilai_mahasiswa')->updateOrInsert(
                [
                    'mahasiswa_id'     => $mhsId,
                    'survey_lokasi_id' => $survey->id,
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
