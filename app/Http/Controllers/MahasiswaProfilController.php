<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaProfilController extends Controller
{
    public function show(Request $request, $mahasiswaId)
    {
        // Mahasiswa + prodi
        $mahasiswa = DB::table('mahasiswa')
            ->leftJoin('program_studi', 'mahasiswa.program_studi_id', '=', 'program_studi.id')
            ->where('mahasiswa.id', $mahasiswaId)
            ->select(['mahasiswa.*', 'program_studi.nama as prodi'])
            ->first();

        abort_if(!$mahasiswa, 404);

        // All kelompok this mahasiswa belongs to (for context switcher)
        $semuaKelompok = DB::table('kelompok_mahasiswa')
            ->join('survey_lokasi', 'kelompok_mahasiswa.survey_lokasi_id', '=', 'survey_lokasi.id')
            ->join('kegiatan',      'survey_lokasi.kegiatan_id',          '=', 'kegiatan.id')
            ->leftJoin('desa',      'survey_lokasi.desa_id',              '=', 'desa.id')
            ->leftJoin('kecamatan', 'desa.kecamatan_id',                  '=', 'kecamatan.id')
            ->where('kelompok_mahasiswa.mahasiswa_id', $mahasiswaId)
            ->select([
                'survey_lokasi.id as survey_id',
                'survey_lokasi.kelompok',
                'kelompok_mahasiswa.is_koordinator',
                'kegiatan.id as kegiatan_id',
                'kegiatan.nama as kegiatan_nama',
                'kegiatan.kegiatan_mulai',
                'kegiatan.kegiatan_selesai',
                'desa.nama as desa',
                'kecamatan.nama as kecamatan',
            ])
            ->orderByDesc('kegiatan.id')
            ->get();

        abort_if($semuaKelompok->isEmpty(), 404);

        // Determine active survey_lokasi
        $surveyLokasiId = $request->query('survey_lokasi_id');
        $surveyLokasi   = $surveyLokasiId
            ? $semuaKelompok->firstWhere('survey_id', (int) $surveyLokasiId)
            : $semuaKelompok->first();

        abort_if(!$surveyLokasi, 404);

        $kegiatan = (object) [
            'id'              => $surveyLokasi->kegiatan_id,
            'nama'            => $surveyLokasi->kegiatan_nama,
            'kegiatan_mulai'  => $surveyLokasi->kegiatan_mulai,
            'kegiatan_selesai'=> $surveyLokasi->kegiatan_selesai,
        ];

        // Pendaftaran data
        $pendaftaran = DB::table('mahasiswa_pendaftaran')
            ->where('mahasiswa_id', $mahasiswaId)
            ->first();

        // Dokumen pendaftaran
        $dokumenPendaftaran = collect();
        if ($pendaftaran) {
            $dokumenPendaftaran = DB::table('mahasiswa_dokumen')
                ->join('kegiatan_dokumen', 'mahasiswa_dokumen.kegiatan_dokumen_id', '=', 'kegiatan_dokumen.id')
                ->where('mahasiswa_dokumen.mahasiswa_pendaftaran_id', $pendaftaran->id)
                ->select([
                    'mahasiswa_dokumen.id',
                    'mahasiswa_dokumen.file_path',
                    'mahasiswa_dokumen.file_name',
                    'mahasiswa_dokumen.file_size',
                    'mahasiswa_dokumen.status',
                    'mahasiswa_dokumen.catatan_verifikasi',
                    'mahasiswa_dokumen.created_at as uploaded_at',
                    'kegiatan_dokumen.nama as dokumen_nama',
                ])
                ->get();
        }

        // Logbook (for selected kelompok)
        $logbookList = DB::table('logbook')
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('survey_lokasi_id', $surveyLokasi->survey_id)
            ->orderByDesc('tanggal')
            ->get();

        // Laporan individu (for selected kelompok)
        $laporanIndividu = DB::table('laporan_individu')
            ->leftJoin('kegiatan_dokumen', 'laporan_individu.kegiatan_dokumen_id', '=', 'kegiatan_dokumen.id')
            ->where('laporan_individu.mahasiswa_id', $mahasiswaId)
            ->where('laporan_individu.survey_lokasi_id', $surveyLokasi->survey_id)
            ->select([
                'laporan_individu.id',
                'laporan_individu.file_path',
                'laporan_individu.file_name',
                'laporan_individu.file_size',
                'laporan_individu.keterangan',
                'laporan_individu.created_at as uploaded_at',
                'kegiatan_dokumen.nama as dokumen_nama',
            ])
            ->get();

        // Laporan akhir (only if koordinator)
        $laporanAkhir = null;
        if ($surveyLokasi->is_koordinator) {
            $laporanAkhir = DB::table('laporan_akhir')
                ->leftJoin('kegiatan_dokumen', 'laporan_akhir.kegiatan_dokumen_id', '=', 'kegiatan_dokumen.id')
                ->where('laporan_akhir.survey_lokasi_id', $surveyLokasi->survey_id)
                ->select([
                    'laporan_akhir.id',
                    'laporan_akhir.file_path',
                    'laporan_akhir.file_name',
                    'laporan_akhir.file_size',
                    'laporan_akhir.keterangan',
                    'laporan_akhir.created_at as uploaded_at',
                    'kegiatan_dokumen.nama as dokumen_nama',
                ])
                ->first();
        }

        // Nilai akhir + catatan
        $nilai = DB::table('nilai_mahasiswa')
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('survey_lokasi_id', $surveyLokasi->survey_id)
            ->select(['nilai_akhir', 'catatan'])
            ->first();

        // Komponen penilaian kegiatan ini
        $komponenPenilaian = DB::table('kegiatan_komponen_penilaian')
            ->where('kegiatan_id', $surveyLokasi->kegiatan_id)
            ->orderBy('urutan')
            ->get();

        // Nilai per komponen untuk mahasiswa ini
        $nilaiKomponenRaw = DB::table('nilai_komponen')
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('survey_lokasi_id', $surveyLokasi->survey_id)
            ->select(['kegiatan_komponen_penilaian_id as komponen_id', 'nilai'])
            ->get();

        // key: komponen_id → nilai
        $nilaiKomponen = $nilaiKomponenRaw->pluck('nilai', 'komponen_id');

        $gradeTable = DB::table('kegiatan_grade')
            ->where('kegiatan_id', $surveyLokasi->kegiatan_id)
            ->orderByDesc('nilai_min')
            ->get();

        return view('mahasiswa.profil', compact(
            'mahasiswa', 'surveyLokasi', 'kegiatan', 'semuaKelompok',
            'pendaftaran', 'dokumenPendaftaran',
            'logbookList', 'laporanIndividu', 'laporanAkhir',
            'nilai', 'komponenPenilaian', 'nilaiKomponen', 'gradeTable'
        ));
    }
}
