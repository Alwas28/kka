<?php

namespace App\Http\Controllers;

use App\Models\JenisKka;
use App\Models\Kegiatan;
use App\Models\Periode;
use App\Models\Tahun;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    /** Urutan & label untuk setiap tahapan kegiatan. */
    private const TAHAPAN_LIST = [
        'survey'         => ['label' => 'Survey',         'urutan' => 1],
        'pendaftaran'    => ['label' => 'Pendaftaran',     'urutan' => 2],
        'verifikasi'     => ['label' => 'Verifikasi',      'urutan' => 3],
        'setup_kelompok' => ['label' => 'Setup Kelompok',  'urutan' => 4],
        'pelaksanaan'    => ['label' => 'Pelaksanaan',     'urutan' => 5],
        'pelaporan'      => ['label' => 'Pelaporan',       'urutan' => 6],
    ];

    /** Nama dokumen pendaftaran yang bersifat tetap (fixed). */
    private const FIXED_PENDAFTARAN = ['Bukti Pembayaran', 'Sertifikat Baca Quran'];

    /** Nama dokumen laporan individu yang bersifat tetap (fixed). */
    private const FIXED_INDIVIDU = ['Logbook'];

    public function index()
    {
        abort_unless(auth()->user()->hasAccess('lihat.kegiatan'), 403, 'Anda tidak memiliki akses untuk melihat halaman ini.');

        $kegiatanList = Kegiatan::with(['jenisKka', 'tahun', 'periode'])->latest()->get();

        return view('kegiatan.index', compact('kegiatanList'));
    }

    public function create()
    {
        abort_unless(auth()->user()->hasAccess('tambah.kegiatan'), 403, 'Anda tidak memiliki akses untuk menambah data.');

        $jenisKkaList = JenisKka::orderBy('nama')->get();
        $tahunList    = Tahun::orderBy('nama', 'desc')->get();
        $periodeList  = Periode::orderBy('nama')->get();

        return view('kegiatan.create', compact('jenisKkaList', 'tahunList', 'periodeList'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('tambah.kegiatan'), 403, 'Anda tidak memiliki akses untuk menambah data.');

        $data = $this->validateKegiatan($request);

        $kegiatan = Kegiatan::create($data);
        $this->saveTahapan($kegiatan, $request);
        $this->saveDokumen($kegiatan, $request);
        $this->saveKomponen($kegiatan, $request);
        $this->saveGrade($kegiatan, $request);

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dibuat.');
    }

    public function edit(Kegiatan $kegiatan)
    {
        abort_unless(auth()->user()->hasAccess('edit.kegiatan'), 403, 'Anda tidak memiliki akses untuk mengubah data.');

        $kegiatan->load(['dokumen', 'tahapan', 'komponen', 'grade']);

        $jenisKkaList = JenisKka::orderBy('nama')->get();
        $tahunList    = Tahun::orderBy('nama', 'desc')->get();
        $periodeList  = Periode::orderBy('nama')->get();

        $dokPendaftaran = $kegiatan->dokumen->where('kategori', 'pendaftaran')->values();
        $lapIndividu    = $kegiatan->dokumen->where('kategori', 'laporan_individu')->values();
        $lapKelompok    = $kegiatan->dokumen->where('kategori', 'laporan_kelompok')->values();

        $tahapanByNama  = $kegiatan->tahapan->keyBy('nama');

        $nextDaftarIdx = 2 + $dokPendaftaran->where('is_fixed', false)->count();
        $nextIndIdx    = 1 + $lapIndividu->where('is_fixed', false)->count();
        $nextKelIdx    = $lapKelompok->count();

        $komponenList    = $kegiatan->komponen;
        $gradeList       = $kegiatan->grade;

        return view('kegiatan.edit', compact(
            'kegiatan', 'jenisKkaList', 'tahunList', 'periodeList',
            'dokPendaftaran', 'lapIndividu', 'lapKelompok',
            'tahapanByNama', 'nextDaftarIdx', 'nextIndIdx', 'nextKelIdx',
            'komponenList', 'gradeList'
        ));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        abort_unless(auth()->user()->hasAccess('edit.kegiatan'), 403, 'Anda tidak memiliki akses untuk mengubah data.');

        $data = $this->validateKegiatan($request);

        $kegiatan->update($data);
        $kegiatan->tahapan()->delete();
        $this->saveTahapan($kegiatan, $request);
        $kegiatan->dokumen()->delete();
        $this->saveDokumen($kegiatan, $request);
        $kegiatan->komponen()->delete();
        $this->saveKomponen($kegiatan, $request);
        $kegiatan->grade()->delete();
        $this->saveGrade($kegiatan, $request);

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        abort_unless(auth()->user()->hasAccess('hapus.kegiatan'), 403, 'Anda tidak memiliki akses untuk menghapus data.');

        $kegiatan->delete();

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }

    public function toggleAktif(Request $request, Kegiatan $kegiatan)
    {
        abort_unless(auth()->user()->hasAccess('edit.kegiatan'), 403);

        $field = $request->input('field');
        abort_unless(in_array($field, ['logbook_aktif', 'laporan_aktif']), 422, 'Field tidak valid.');

        $kegiatan->update([$field => !$kegiatan->$field]);

        $label = $field === 'logbook_aktif' ? 'Logbook' : 'Laporan';
        $state = $kegiatan->$field ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "{$label} berhasil {$state}.");
    }

    public function berlangsung()
    {
        abort_unless(auth()->user()->hasAccess('lihat.kegiatan'), 403, 'Anda tidak memiliki akses untuk melihat halaman ini.');

        $kegiatanList = Kegiatan::with(['jenisKka', 'tahun', 'periode', 'tahapan'])
            ->whereDate('kegiatan_mulai', '<=', today())
            ->whereDate('kegiatan_selesai', '>=', today())
            ->latest()
            ->get();

        return view('kegiatan.berlangsung', compact('kegiatanList'));
    }

    public function selesai()
    {
        abort_unless(auth()->user()->hasAccess('lihat.kegiatan'), 403, 'Anda tidak memiliki akses untuk melihat halaman ini.');

        $kegiatanList = Kegiatan::with(['jenisKka', 'tahun', 'periode'])
            ->whereDate('kegiatan_selesai', '<', today())
            ->orderBy('kegiatan_selesai', 'desc')
            ->get();

        return view('kegiatan.selesai', compact('kegiatanList'));
    }

    // ────────────────────────────────────────────
    // PRIVATE HELPERS
    // ────────────────────────────────────────────

    private function validateKegiatan(Request $request): array
    {
        $request->validate([
            'nama'             => 'required|string|max:255',
            'jenis_kka_id'     => 'required|exists:jenis_kka,id',
            'tahun_id'         => 'required|exists:tahun,id',
            'periode_id'       => 'required|exists:periode,id',
            'kegiatan_mulai'   => 'required|date',
            'kegiatan_selesai' => 'required|date|after_or_equal:kegiatan_mulai',
        ]);

        return $request->only(['nama', 'jenis_kka_id', 'tahun_id', 'periode_id', 'kegiatan_mulai', 'kegiatan_selesai']);
    }

    private function saveTahapan(Kegiatan $kegiatan, Request $request): void
    {
        foreach (self::TAHAPAN_LIST as $key => $info) {
            $data = $request->input("tahapan.$key");
            if (empty($data['aktif'])) continue;

            $kegiatan->tahapan()->create([
                'nama'   => $key,
                'urutan' => $info['urutan'],
                'mulai'  => $data['mulai']   ?: null,
                'selesai'=> $data['selesai'] ?: null,
            ]);
        }
    }

    private function saveDokumen(Kegiatan $kegiatan, Request $request): void
    {
        $dokDaftar   = $request->input('dok_daftar', []);
        $lapIndividu = $request->input('lap_individu', []);
        $lapKelompok = $request->input('lap_kelompok', []);

        // — Fixed: Dokumen Pendaftaran —
        foreach (self::FIXED_PENDAFTARAN as $idx => $nama) {
            $kegiatan->dokumen()->create([
                'kategori' => 'pendaftaran',
                'nama'     => $nama,
                'is_wajib' => isset($dokDaftar[$idx]['wajib']),
                'is_fixed' => true,
                'urutan'   => $idx,
            ]);
        }
        // Custom: Dokumen Pendaftaran
        foreach ($dokDaftar as $idx => $dok) {
            if ($idx < count(self::FIXED_PENDAFTARAN) || empty($dok['nama'])) continue;
            $kegiatan->dokumen()->create([
                'kategori' => 'pendaftaran',
                'nama'     => $dok['nama'],
                'is_wajib' => isset($dok['wajib']),
                'is_fixed' => false,
                'urutan'   => $idx,
            ]);
        }

        // — Fixed: Laporan Individu —
        foreach (self::FIXED_INDIVIDU as $idx => $nama) {
            $kegiatan->dokumen()->create([
                'kategori' => 'laporan_individu',
                'nama'     => $nama,
                'is_wajib' => isset($lapIndividu[$idx]['wajib']),
                'is_fixed' => true,
                'urutan'   => $idx,
            ]);
        }
        // Custom: Laporan Individu
        foreach ($lapIndividu as $idx => $dok) {
            if ($idx < count(self::FIXED_INDIVIDU) || empty($dok['nama'])) continue;
            $kegiatan->dokumen()->create([
                'kategori' => 'laporan_individu',
                'nama'     => $dok['nama'],
                'is_wajib' => isset($dok['wajib']),
                'is_fixed' => false,
                'urutan'   => $idx,
            ]);
        }

        // — Custom: Laporan Kelompok —
        foreach ($lapKelompok as $idx => $dok) {
            if (empty($dok['nama'])) continue;
            $kegiatan->dokumen()->create([
                'kategori' => 'laporan_kelompok',
                'nama'     => $dok['nama'],
                'is_wajib' => isset($dok['wajib']),
                'is_fixed' => false,
                'urutan'   => $idx,
            ]);
        }
    }

    private function saveKomponen(Kegiatan $kegiatan, Request $request): void
    {
        $items = $request->input('komponen', []);
        foreach ($items as $idx => $item) {
            if (empty($item['nama']) || !isset($item['persentase'])) continue;
            $kegiatan->komponen()->create([
                'nama'       => $item['nama'],
                'persentase' => max(0, min(100, (int) $item['persentase'])),
                'urutan'     => $idx,
            ]);
        }
    }

    private function saveGrade(Kegiatan $kegiatan, Request $request): void
    {
        $items = $request->input('grade', []);
        foreach ($items as $idx => $item) {
            if (empty($item['grade']) || !isset($item['nilai_min']) || !isset($item['nilai_max'])) continue;
            $kegiatan->grade()->create([
                'grade'     => $item['grade'],
                'nilai_min' => max(0, min(100, (float) $item['nilai_min'])),
                'nilai_max' => max(0, min(100, (float) $item['nilai_max'])),
                'urutan'    => $idx,
            ]);
        }
    }
}
