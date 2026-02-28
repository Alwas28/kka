<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.program-studi'), 403, 'Anda tidak memiliki akses untuk melihat halaman ini.');

        $fakultasList = Fakultas::orderBy('nama')->get();
        $selectedFakultas = $request->get('fakultas_id');

        $query = ProgramStudi::with('fakultas')->latest();

        if ($selectedFakultas) {
            $query->where('fakultas_id', $selectedFakultas);
        }

        $programStudi = $query->get();

        return view('program-studi.index', compact('programStudi', 'fakultasList', 'selectedFakultas'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('tambah.program-studi'), 403, 'Anda tidak memiliki akses untuk menambah data.');

        $request->validate([
            'fakultas_id' => 'required|exists:fakultas,id',
            'kode'        => 'required|string|max:20|unique:program_studi,kode',
            'nama'        => 'required|string|max:255',
            'jenjang'     => 'required|in:S1,S2,S3,D3,D4',
            'keterangan'  => 'nullable|string|max:255',
        ]);

        ProgramStudi::create($request->only('fakultas_id', 'kode', 'nama', 'jenjang', 'keterangan'));

        return redirect()->route('program-studi.index', ['fakultas_id' => $request->fakultas_id])
            ->with('success', 'Program Studi berhasil ditambahkan.');
    }

    public function update(Request $request, ProgramStudi $programStudi)
    {
        abort_unless(auth()->user()->hasAccess('edit.program-studi'), 403, 'Anda tidak memiliki akses untuk mengubah data.');

        $request->validate([
            'fakultas_id' => 'required|exists:fakultas,id',
            'kode'        => 'required|string|max:20|unique:program_studi,kode,' . $programStudi->id,
            'nama'        => 'required|string|max:255',
            'jenjang'     => 'required|in:S1,S2,S3,D3,D4',
            'keterangan'  => 'nullable|string|max:255',
        ]);

        $programStudi->update($request->only('fakultas_id', 'kode', 'nama', 'jenjang', 'keterangan'));

        return redirect()->route('program-studi.index', ['fakultas_id' => $request->fakultas_id])
            ->with('success', 'Program Studi berhasil diperbarui.');
    }

    public function destroy(Request $request, ProgramStudi $programStudi)
    {
        abort_unless(auth()->user()->hasAccess('hapus.program-studi'), 403, 'Anda tidak memiliki akses untuk menghapus data.');

        $fakultasId = $programStudi->fakultas_id;
        $programStudi->delete();

        return redirect()->route('program-studi.index', ['fakultas_id' => $fakultasId])
            ->with('success', 'Program Studi berhasil dihapus.');
    }
}
