<?php

namespace App\Http\Controllers;

use App\Models\KegiatanDokumen;
use App\Models\LaporanAkhir;
use App\Models\LaporanIndividu;
use App\Models\Logbook;
use App\Models\NilaiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PelaksanaanController extends Controller
{
    private function getMahasiswa()
    {
        return auth('mahasiswa')->user();
    }

    private function getKelompok($mahasiswa)
    {
        return $mahasiswa->kelompok()
            ->with([
                'desa.kecamatan.kabupaten',
                'kegiatan.dokumen',
                'dosenPembimbing',
                'peserta.programStudi',
            ])
            ->first();
    }

    private function cekSudahDinilai($mahasiswaId, $surveyLokasiId): bool
    {
        return NilaiMahasiswa::where('mahasiswa_id', $mahasiswaId)
            ->where('survey_lokasi_id', $surveyLokasiId)
            ->whereNotNull('nilai_akhir')
            ->exists();
    }

    // ─── INDEX ────────────────────────────────────────────────────────────────

    public function index()
    {
        $mahasiswa = $this->getMahasiswa();
        $kelompok  = $this->getKelompok($mahasiswa);

        abort_unless($kelompok, 404, 'Anda belum terdaftar dalam kelompok KKA.');

        $kegiatan = $kelompok->kegiatan;

        $isKoordinator = $kelompok->peserta
            ->where('id', $mahasiswa->id)
            ->first()
            ?->pivot
            ?->is_koordinator ?? false;

        $dokumen = $kegiatan?->dokumen ?? collect();

        $hasLogbook = $dokumen->where('kategori', 'laporan_individu')
                              ->where('nama', 'Logbook')
                              ->isNotEmpty();

        $dokumenIndividu = $dokumen->where('kategori', 'laporan_individu')
                                   ->filter(fn($d) => $d->nama !== 'Logbook')
                                   ->sortBy('urutan')
                                   ->values();

        $dokumenKelompok = $dokumen->where('kategori', 'laporan_kelompok')
                                   ->sortBy('urutan')
                                   ->values();

        $logbooks = Logbook::where('mahasiswa_id', $mahasiswa->id)
            ->where('survey_lokasi_id', $kelompok->id)
            ->orderByDesc('tanggal')
            ->get();

        $uploadIndividu = LaporanIndividu::where('mahasiswa_id', $mahasiswa->id)
            ->where('survey_lokasi_id', $kelompok->id)
            ->get()
            ->keyBy('kegiatan_dokumen_id');

        $uploadKelompok = LaporanAkhir::where('survey_lokasi_id', $kelompok->id)
            ->get()
            ->keyBy('kegiatan_dokumen_id');

        $nilai = NilaiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->where('survey_lokasi_id', $kelompok->id)
            ->with('dpl')
            ->first();

        $gradeTable = $kegiatan
            ? DB::table('kegiatan_grade')->where('kegiatan_id', $kegiatan->id)->orderByDesc('nilai_min')->get()
            : collect();

        $sudahDinilai = $nilai && $nilai->nilai_akhir !== null;

        return view('mahasiswa.pelaksanaan.index', compact(
            'mahasiswa', 'kelompok', 'kegiatan', 'isKoordinator',
            'hasLogbook', 'dokumenIndividu', 'dokumenKelompok',
            'logbooks', 'uploadIndividu', 'uploadKelompok',
            'nilai', 'gradeTable', 'sudahDinilai'
        ));
    }

    // ─── LOGBOOK ──────────────────────────────────────────────────────────────

    public function storeLogbook(Request $request)
    {
        $mahasiswa = $this->getMahasiswa();
        $kelompok  = $this->getKelompok($mahasiswa);
        abort_unless($kelompok, 404);

        abort_if(
            $this->cekSudahDinilai($mahasiswa->id, $kelompok->id),
            403, 'Tidak dapat diubah karena nilai sudah diinputkan.'
        );

        $request->validate([
            'tanggal'            => 'required|date',
            'kegiatan_dilakukan' => 'required|string',
            'lokasi'             => 'nullable|string|max:255',
        ]);

        $exists = Logbook::where('mahasiswa_id', $mahasiswa->id)
            ->where('survey_lokasi_id', $kelompok->id)
            ->whereDate('tanggal', $request->tanggal)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Logbook untuk tanggal ini sudah ada. Gunakan tombol edit.');
        }

        Logbook::create([
            'mahasiswa_id'       => $mahasiswa->id,
            'survey_lokasi_id'   => $kelompok->id,
            'tanggal'            => $request->tanggal,
            'kegiatan_dilakukan' => $request->kegiatan_dilakukan,
            'lokasi'             => $request->lokasi,
        ]);

        return back()->with('success', 'Logbook berhasil ditambahkan.');
    }

    public function updateLogbook(Request $request, Logbook $logbook)
    {
        $mahasiswa = $this->getMahasiswa();
        abort_unless($logbook->mahasiswa_id === $mahasiswa->id, 403);

        abort_if(
            $this->cekSudahDinilai($mahasiswa->id, $logbook->survey_lokasi_id),
            403, 'Tidak dapat diubah karena nilai sudah diinputkan.'
        );

        $request->validate([
            'tanggal'            => 'required|date',
            'kegiatan_dilakukan' => 'required|string',
            'lokasi'             => 'nullable|string|max:255',
        ]);

        $logbook->update($request->only('tanggal', 'kegiatan_dilakukan', 'lokasi'));

        return back()->with('success', 'Logbook berhasil diperbarui.');
    }

    public function destroyLogbook(Logbook $logbook)
    {
        $mahasiswa = $this->getMahasiswa();
        abort_unless($logbook->mahasiswa_id === $mahasiswa->id, 403);

        abort_if(
            $this->cekSudahDinilai($mahasiswa->id, $logbook->survey_lokasi_id),
            403, 'Tidak dapat diubah karena nilai sudah diinputkan.'
        );

        $logbook->delete();

        return back()->with('success', 'Logbook berhasil dihapus.');
    }

    // ─── LAPORAN INDIVIDU ─────────────────────────────────────────────────────

    public function uploadLaporanIndividu(Request $request, $dokumen)
    {
        $mahasiswa = $this->getMahasiswa();
        $kelompok  = $this->getKelompok($mahasiswa);
        abort_unless($kelompok, 404);

        abort_if(
            $this->cekSudahDinilai($mahasiswa->id, $kelompok->id),
            403, 'Tidak dapat diubah karena nilai sudah diinputkan.'
        );

        $dok = KegiatanDokumen::where('id', $dokumen)
            ->where('kegiatan_id', $kelompok->kegiatan_id)
            ->where('kategori', 'laporan_individu')
            ->where('nama', '!=', 'Logbook')
            ->firstOrFail();

        $request->validate([
            'file'       => 'required|file|mimes:pdf|max:10240',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $existing = LaporanIndividu::where('mahasiswa_id', $mahasiswa->id)
            ->where('survey_lokasi_id', $kelompok->id)
            ->where('kegiatan_dokumen_id', $dok->id)
            ->first();

        if ($existing) {
            Storage::disk('public')->delete($existing->file_path);
            $existing->delete();
        }

        $file     = $request->file('file');
        $dir      = 'laporan_individu/' . $kelompok->id;
        $fileName = $mahasiswa->id . '_' . $dok->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs($dir, $fileName, 'public');

        LaporanIndividu::create([
            'mahasiswa_id'        => $mahasiswa->id,
            'survey_lokasi_id'    => $kelompok->id,
            'kegiatan_dokumen_id' => $dok->id,
            'file_path'           => $path,
            'file_name'           => $file->getClientOriginalName(),
            'file_size'           => $file->getSize(),
            'keterangan'          => $request->keterangan,
        ]);

        return back()->with('success', '"' . $dok->nama . '" berhasil diupload.');
    }

    public function hapusLaporanIndividu($dokumen)
    {
        $mahasiswa = $this->getMahasiswa();
        $kelompok  = $this->getKelompok($mahasiswa);
        abort_unless($kelompok, 404);

        abort_if(
            $this->cekSudahDinilai($mahasiswa->id, $kelompok->id),
            403, 'Tidak dapat diubah karena nilai sudah diinputkan.'
        );

        $laporan = LaporanIndividu::where('mahasiswa_id', $mahasiswa->id)
            ->where('survey_lokasi_id', $kelompok->id)
            ->where('kegiatan_dokumen_id', $dokumen)
            ->firstOrFail();

        Storage::disk('public')->delete($laporan->file_path);
        $laporan->delete();

        return back()->with('success', 'Laporan berhasil dihapus.');
    }

    // ─── LAPORAN AKHIR KELOMPOK ───────────────────────────────────────────────

    public function uploadLaporanAkhir(Request $request, $dokumen)
    {
        $mahasiswa = $this->getMahasiswa();
        $kelompok  = $this->getKelompok($mahasiswa);
        abort_unless($kelompok, 404);

        $isKoordinator = $kelompok->peserta
            ->where('id', $mahasiswa->id)->first()
            ?->pivot?->is_koordinator ?? false;
        abort_unless($isKoordinator, 403, 'Hanya koordinator kelompok yang dapat mengupload laporan akhir.');

        abort_if(
            $this->cekSudahDinilai($mahasiswa->id, $kelompok->id),
            403, 'Tidak dapat diubah karena nilai sudah diinputkan.'
        );

        $dok = KegiatanDokumen::where('id', $dokumen)
            ->where('kegiatan_id', $kelompok->kegiatan_id)
            ->where('kategori', 'laporan_kelompok')
            ->firstOrFail();

        $request->validate([
            'file'       => 'required|file|mimes:pdf|max:20480',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $existing = LaporanAkhir::where('survey_lokasi_id', $kelompok->id)
            ->where('kegiatan_dokumen_id', $dok->id)
            ->first();

        if ($existing) {
            Storage::disk('public')->delete($existing->file_path);
            $existing->delete();
        }

        $file     = $request->file('file');
        $dir      = 'laporan_akhir/' . $kelompok->id;
        $fileName = 'laporan_akhir_' . $dok->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs($dir, $fileName, 'public');

        LaporanAkhir::create([
            'survey_lokasi_id'    => $kelompok->id,
            'mahasiswa_id'        => $mahasiswa->id,
            'kegiatan_dokumen_id' => $dok->id,
            'file_path'           => $path,
            'file_name'           => $file->getClientOriginalName(),
            'file_size'           => $file->getSize(),
            'keterangan'          => $request->keterangan,
        ]);

        return back()->with('success', '"' . $dok->nama . '" berhasil diupload.');
    }

    public function hapusLaporanAkhir($dokumen)
    {
        $mahasiswa = $this->getMahasiswa();
        $kelompok  = $this->getKelompok($mahasiswa);
        abort_unless($kelompok, 404);

        $isKoordinator = $kelompok->peserta
            ->where('id', $mahasiswa->id)->first()
            ?->pivot?->is_koordinator ?? false;
        abort_unless($isKoordinator, 403);

        abort_if(
            $this->cekSudahDinilai($mahasiswa->id, $kelompok->id),
            403, 'Tidak dapat diubah karena nilai sudah diinputkan.'
        );

        $laporan = LaporanAkhir::where('survey_lokasi_id', $kelompok->id)
            ->where('kegiatan_dokumen_id', $dokumen)
            ->firstOrFail();

        Storage::disk('public')->delete($laporan->file_path);
        $laporan->delete();

        return back()->with('success', 'Laporan akhir berhasil dihapus.');
    }
}
