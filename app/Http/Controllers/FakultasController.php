<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use Illuminate\Http\Request;

class FakultasController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->hasAccess('lihat.fakultas'), 403, 'Anda tidak memiliki akses untuk melihat halaman ini.');

        $fakultas = Fakultas::latest()->get();
        return view('fakultas.index', compact('fakultas'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('tambah.fakultas'), 403, 'Anda tidak memiliki akses untuk menambah data.');

        $request->validate([
            'kode'       => 'required|string|max:20|unique:fakultas,kode',
            'nama'       => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        Fakultas::create($request->only('kode', 'nama', 'keterangan'));

        return redirect()->route('fakultas.index')->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function update(Request $request, Fakultas $fakultas)
    {
        abort_unless(auth()->user()->hasAccess('edit.fakultas'), 403, 'Anda tidak memiliki akses untuk mengubah data.');

        $request->validate([
            'kode'       => 'required|string|max:20|unique:fakultas,kode,' . $fakultas->id,
            'nama'       => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $fakultas->update($request->only('kode', 'nama', 'keterangan'));

        return redirect()->route('fakultas.index')->with('success', 'Fakultas berhasil diperbarui.');
    }

    public function destroy(Fakultas $fakultas)
    {
        abort_unless(auth()->user()->hasAccess('hapus.fakultas'), 403, 'Anda tidak memiliki akses untuk menghapus data.');

        $fakultas->delete();

        return redirect()->route('fakultas.index')->with('success', 'Fakultas berhasil dihapus.');
    }
}
