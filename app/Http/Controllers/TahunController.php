<?php

namespace App\Http\Controllers;

use App\Models\Tahun;
use Illuminate\Http\Request;

class TahunController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->hasAccess('lihat.tahun'), 403, 'Anda tidak memiliki akses untuk melihat halaman ini.');

        $tahunList = Tahun::latest()->get();

        return view('tahun.index', compact('tahunList'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('tambah.tahun'), 403, 'Anda tidak memiliki akses untuk menambah data.');

        $request->validate([
            'nama'       => 'required|string|max:255|unique:tahun,nama',
            'keterangan' => 'nullable|string|max:255',
        ]);

        Tahun::create($request->only('nama', 'keterangan'));

        return redirect()->route('tahun.index')->with('success', 'Tahun berhasil ditambahkan.');
    }

    public function update(Request $request, Tahun $tahun)
    {
        abort_unless(auth()->user()->hasAccess('edit.tahun'), 403, 'Anda tidak memiliki akses untuk mengubah data.');

        $request->validate([
            'nama'       => 'required|string|max:255|unique:tahun,nama,' . $tahun->id,
            'keterangan' => 'nullable|string|max:255',
        ]);

        $tahun->update($request->only('nama', 'keterangan'));

        return redirect()->route('tahun.index')->with('success', 'Tahun berhasil diperbarui.');
    }

    public function destroy(Tahun $tahun)
    {
        abort_unless(auth()->user()->hasAccess('hapus.tahun'), 403, 'Anda tidak memiliki akses untuk menghapus data.');

        $tahun->delete();

        return redirect()->route('tahun.index')->with('success', 'Tahun berhasil dihapus.');
    }
}
