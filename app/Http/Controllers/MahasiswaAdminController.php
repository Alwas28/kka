<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Mahasiswa;
use App\Models\MahasiswaDokumen;
use App\Models\MahasiswaLevel;
use App\Models\MahasiswaNotifikasi;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MahasiswaAdminController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.mahasiswa-admin'), 403);

        $query = Mahasiswa::with(['programStudi.fakultas', 'level'])->latest();

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($level = $request->level) {
            $query->where('mahasiswa_level_id', $level);
        }

        if ($prodi = $request->prodi) {
            $query->where('program_studi_id', $prodi);
        }

        $mahasiswaList = $query->paginate(20)->withQueryString();
        $levels        = MahasiswaLevel::orderBy('id')->get();
        $prodiList     = ProgramStudi::with('fakultas')->orderBy('nama')->get();
        $kegiatanList  = Kegiatan::orderByDesc('id')->get();

        return view('mahasiswa.admin-index', compact('mahasiswaList', 'levels', 'prodiList', 'kegiatanList'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        abort_unless(auth()->user()->hasAccess('edit.mahasiswa-admin'), 403);

        $data = $request->validate([
            'nim'                => ['required', 'string', 'max:20', Rule::unique('mahasiswa', 'nim')->ignore($mahasiswa->id)],
            'nama'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email', Rule::unique('mahasiswa', 'email')->ignore($mahasiswa->id)],
            'program_studi_id'   => ['required', 'exists:program_studi,id'],
            'mahasiswa_level_id' => ['required', 'exists:mahasiswa_level,id'],
            'password'           => ['nullable', 'string', 'min:8'],
            'kegiatan_id'        => ['nullable', 'exists:kegiatan,id'],
        ]);

        $update = [
            'nim'                => $data['nim'],
            'nama'               => $data['nama'],
            'email'              => $data['email'],
            'program_studi_id'   => $data['program_studi_id'],
            'mahasiswa_level_id' => $data['mahasiswa_level_id'],
        ];

        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $mahasiswa->update($update);

        if (!empty($data['kegiatan_id']) && $mahasiswa->pendaftaran) {
            $mahasiswa->pendaftaran->update(['kegiatan_id' => $data['kegiatan_id']]);
        }

        return back()->with('success', "Data <strong>{$mahasiswa->nama}</strong> berhasil diperbarui.");
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        abort_unless(auth()->user()->hasAccess('edit.mahasiswa-admin'), 403);

        $nama = $mahasiswa->nama;
        $nim  = $mahasiswa->nim;

        // Hapus data terkait sebelum menghapus mahasiswa
        $mahasiswa->load('pendaftaran');
        if ($mahasiswa->pendaftaran) {
            MahasiswaDokumen::where('mahasiswa_pendaftaran_id', $mahasiswa->pendaftaran->id)->delete();
            $mahasiswa->pendaftaran->delete();
        }
        MahasiswaNotifikasi::where('mahasiswa_id', $mahasiswa->id)->delete();
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.admin.index')
            ->with('success', "Data mahasiswa <strong>{$nama}</strong> (NIM: {$nim}) telah dihapus secara permanen.");
    }
}
