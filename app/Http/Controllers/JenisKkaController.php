<?php

namespace App\Http\Controllers;

use App\Models\JenisKka;
use Illuminate\Http\Request;

class JenisKkaController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->hasAccess('lihat.jenis-kka'), 403, 'Anda tidak memiliki akses untuk melihat halaman ini.');

        $jenisKkaList = JenisKka::latest()->get();

        return view('jenis-kka.index', compact('jenisKkaList'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('tambah.jenis-kka'), 403, 'Anda tidak memiliki akses untuk menambah data.');

        $request->validate([
            'nama'       => 'required|string|max:255|unique:jenis_kka,nama',
            'keterangan' => 'nullable|string|max:255',
        ]);

        JenisKka::create($request->only('nama', 'keterangan'));

        return redirect()->route('jenis-kka.index')->with('success', 'Jenis KKA berhasil ditambahkan.');
    }

    public function update(Request $request, JenisKka $jenisKka)
    {
        abort_unless(auth()->user()->hasAccess('edit.jenis-kka'), 403, 'Anda tidak memiliki akses untuk mengubah data.');

        $request->validate([
            'nama'       => 'required|string|max:255|unique:jenis_kka,nama,' . $jenisKka->id,
            'keterangan' => 'nullable|string|max:255',
        ]);

        $jenisKka->update($request->only('nama', 'keterangan'));

        return redirect()->route('jenis-kka.index')->with('success', 'Jenis KKA berhasil diperbarui.');
    }

    public function destroy(JenisKka $jenisKka)
    {
        abort_unless(auth()->user()->hasAccess('hapus.jenis-kka'), 403, 'Anda tidak memiliki akses untuk menghapus data.');

        $jenisKka->delete();

        return redirect()->route('jenis-kka.index')->with('success', 'Jenis KKA berhasil dihapus.');
    }
}
