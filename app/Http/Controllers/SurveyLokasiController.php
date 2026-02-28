<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Provinsi;
use App\Models\SurveyLokasi;
use App\Models\User;
use Illuminate\Http\Request;

class SurveyLokasiController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.survey'), 403);

        $isAdmin = auth()->user()->hasAccess('edit.survey');

        $query = SurveyLokasi::with(['desa.kecamatan.kabupaten.provinsi', 'surveyor', 'kegiatan.tahapan'])->latest();

        // Non-admin hanya melihat survey yang ditugaskan untuknya (berdasarkan email/id)
        if (!$isAdmin) {
            $user = auth()->user();
            $query->where(function ($q) use ($user) {
                $q->where('surveyor_id', $user->id)
                  ->orWhereHas('surveyor', fn($s) => $s->where('email', $user->email));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->whereHas('desa', fn($d) => $d->where('nama', 'like', "%{$q}%"))
                   ->orWhereHas('surveyor', fn($s) => $s->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"));
            });
        }

        $surveys  = $query->get();
        $provinsi = Provinsi::orderBy('nama')->get();
        $users    = User::orderBy('name')->get();
        $kegiatan = Kegiatan::orderBy('nama')->get();

        return view('survey.index', compact('surveys', 'provinsi', 'users', 'kegiatan', 'isAdmin'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('tambah.survey'), 403);

        $request->validate([
            'desa_id'      => 'required|exists:desa,id',
            'surveyor_id'  => 'required|exists:users,id',
            'kegiatan_id'  => 'nullable|exists:kegiatan,id',
            'tim_anggota'  => 'nullable|string',
        ]);

        SurveyLokasi::create([
            'desa_id'      => $request->desa_id,
            'surveyor_id'  => $request->surveyor_id,
            'kegiatan_id'  => $request->kegiatan_id,
            'tim_anggota'  => $request->filled('tim_anggota') ? trim($request->tim_anggota) : null,
            'status'       => 'belum_survey',
        ]);

        return redirect()->route('survey.index')->with('success', 'Lokasi survey berhasil ditambahkan.');
    }

    public function update(Request $request, SurveyLokasi $survey)
    {
        abort_unless(auth()->user()->hasAccess('edit.survey'), 403);

        $survey->load('kegiatan.tahapan');
        $this->checkSurveyPeriod($survey);

        $request->validate([
            'desa_id'      => 'required|exists:desa,id',
            'surveyor_id'  => 'required|exists:users,id',
            'kegiatan_id'  => 'nullable|exists:kegiatan,id',
            'tim_anggota'  => 'nullable|string',
        ]);

        $survey->update([
            'desa_id'      => $request->desa_id,
            'surveyor_id'  => $request->surveyor_id,
            'kegiatan_id'  => $request->kegiatan_id,
            'tim_anggota'  => $request->filled('tim_anggota') ? trim($request->tim_anggota) : null,
        ]);

        return redirect()->route('survey.index')->with('success', 'Lokasi survey berhasil diperbarui.');
    }

    public function destroy(SurveyLokasi $survey)
    {
        abort_unless(auth()->user()->hasAccess('hapus.survey'), 403);

        $survey->load('kegiatan.tahapan');
        $this->checkSurveyPeriod($survey);

        $survey->delete();

        return redirect()->route('survey.index')->with('success', 'Lokasi survey berhasil dihapus.');
    }

    public function hasilSurvey(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.survey'), 403);

        $query = SurveyLokasi::with(['desa.kecamatan.kabupaten.provinsi', 'surveyor', 'kegiatan'])
            ->whereIn('status', ['sudah_survey', 'disetujui', 'ditolak'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->whereHas('desa', fn($d) => $d->where('nama', 'like', "%{$q}%"))
                   ->orWhere('nama_kades', 'like', "%{$q}%");
            });
        }

        $surveys = $query->get();

        return view('survey.hasil', compact('surveys'));
    }

    public function isiSurvey(SurveyLokasi $survey)
    {
        abort_unless(auth()->user()->hasAccess('isi.survey'), 403);

        $this->authorizeSurveyor($survey);

        $survey->load('desa.kecamatan.kabupaten.provinsi', 'surveyor', 'kegiatan.tahapan');
        $this->checkSurveyPeriod($survey);

        return view('survey.form-isi', compact('survey'));
    }

    public function simpanSurvey(Request $request, SurveyLokasi $survey)
    {
        abort_unless(auth()->user()->hasAccess('isi.survey'), 403);

        $this->authorizeSurveyor($survey);

        $survey->load('kegiatan.tahapan');
        $this->checkSurveyPeriod($survey);

        $request->validate([
            'nama_kades'             => 'nullable|string|max:255',
            'no_hp_kades'            => 'nullable|string|max:20',
            'pemberi_informasi'      => 'nullable|string|max:255',
            'rencana_posko'          => 'nullable|in:rumah_kades,rumah_warga,lainnya',
            'rencana_posko_lainnya'  => 'nullable|string|max:255',
            'kondisi_air'            => 'nullable|string',
            'kondisi_listrik'        => 'nullable|string',
            'kondisi_transportasi'   => 'nullable|string',
            'deskripsi'              => 'nullable|string',
            'gmaps_url'              => 'nullable|url|max:500',
            'rekomendasi'            => 'required|in:0,1',
            'alasan_rekomendasi'     => 'nullable|string',
        ]);

        $survey->update([
            'nama_kades'            => $request->nama_kades,
            'no_hp_kades'           => $request->no_hp_kades,
            'pemberi_informasi'     => $request->pemberi_informasi,
            'rencana_posko'         => $request->rencana_posko,
            'rencana_posko_lainnya' => $request->rencana_posko === 'lainnya' ? $request->rencana_posko_lainnya : null,
            'kondisi_air'           => $request->kondisi_air,
            'kondisi_listrik'       => $request->kondisi_listrik,
            'kondisi_transportasi'  => $request->kondisi_transportasi,
            'deskripsi'             => $request->deskripsi,
            'gmaps_url'             => $request->gmaps_url,
            'rekomendasi'           => $request->rekomendasi,
            'alasan_rekomendasi'    => $request->alasan_rekomendasi,
            'status'                => 'sudah_survey',
            'surveyed_at'           => now(),
        ]);

        return redirect()->route('survey.index')->with('success', 'Hasil survey berhasil disimpan.');
    }

    public function setujui(Request $request, SurveyLokasi $survey)
    {
        abort_unless(auth()->user()->hasAccess('verifikasi.survey'), 403);

        $request->validate([
            'disetujui'       => 'required|in:0,1',
            'catatan_panitia' => 'nullable|string',
        ]);

        $survey->update([
            'disetujui'       => $request->disetujui,
            'catatan_panitia' => $request->catatan_panitia,
            'status'          => $request->disetujui ? 'disetujui' : 'ditolak',
            'approved_at'     => now(),
        ]);

        $label = $request->disetujui ? 'disetujui' : 'ditolak';

        return redirect()->route('survey.hasil')->with('success', "Survey berhasil {$label}.");
    }

    public function dataLokasi(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.data-lokasi'), 403);

        $query = SurveyLokasi::with(['desa.kecamatan.kabupaten.provinsi', 'surveyor', 'kegiatan'])
            ->withCount(['peserta', 'dosenPembimbing'])
            ->whereNotNull('kelompok')
            ->orderBy('kelompok');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->whereHas('desa', fn($d) => $d->where('nama', 'like', "%{$q}%"))
                   ->orWhere('kelompok', 'like', "%{$q}%");
            });
        }

        if ($request->filled('kegiatan_id')) {
            $query->where('kegiatan_id', $request->kegiatan_id);
        }

        $surveys  = $query->get();
        $kegiatan = \App\Models\Kegiatan::orderBy('nama')->get();

        return view('survey.data-lokasi', compact('surveys', 'kegiatan'));
    }

    public function setKelompok(Request $request, SurveyLokasi $survey)
    {
        abort_unless(auth()->user()->hasAccess('atur.kelompok'), 403);
        abort_unless($survey->status === 'disetujui', 422, 'Hanya lokasi yang disetujui yang dapat diberi kelompok.');

        $request->validate([
            'kelompok' => 'required|integer|min:1|max:9999',
        ]);

        $survey->update(['kelompok' => $request->kelompok]);

        return redirect()->route('survey.hasil')->with('success', "Lokasi berhasil ditetapkan ke Kelompok {$request->kelompok}.");
    }

    /**
     * Batalkan request jika berada di luar periode survey yang ditentukan di tahapan kegiatan.
     */
    private function checkSurveyPeriod(SurveyLokasi $survey): void
    {
        if (!$survey->isSurveyPeriodOpen()) {
            $info = $survey->getSurveyPeriodInfo();
            $msg  = $info
                ? "Pengisian/perubahan survey hanya dapat dilakukan pada periode {$info}."
                : 'Saat ini bukan periode survey yang telah ditentukan.';
            abort(403, $msg);
        }
    }

    /**
     * Pastikan user adalah surveyor yang ditugaskan (email/id cocok) atau admin (edit.survey).
     */
    private function authorizeSurveyor(SurveyLokasi $survey): void
    {
        $user       = auth()->user();
        $isAdmin    = $user->hasAccess('edit.survey');
        $isSurveyor = $survey->surveyor_id === $user->id
                   || ($survey->surveyor && $survey->surveyor->email === $user->email);

        abort_unless($isSurveyor || $isAdmin, 403, 'Anda tidak ditugaskan sebagai surveyor untuk lokasi ini.');
    }
}
