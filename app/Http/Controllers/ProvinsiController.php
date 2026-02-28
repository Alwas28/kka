<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use Illuminate\Http\Request;

class ProvinsiController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->hasAccess('lihat.provinsi'), 403);

        $provinsi = Provinsi::withCount('kabupaten')->latest()->get();
        return view('provinsi.index', compact('provinsi'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('tambah.provinsi'), 403);

        $request->validate([
            'kode' => 'required|string|max:10|unique:provinsi,kode',
            'nama' => 'required|string|max:255',
        ]);

        Provinsi::create($request->only('kode', 'nama'));

        return redirect()->route('provinsi.index')->with('success', 'Provinsi berhasil ditambahkan.');
    }

    public function update(Request $request, Provinsi $provinsi)
    {
        abort_unless(auth()->user()->hasAccess('edit.provinsi'), 403);

        $request->validate([
            'kode' => 'required|string|max:10|unique:provinsi,kode,' . $provinsi->id,
            'nama' => 'required|string|max:255',
        ]);

        $provinsi->update($request->only('kode', 'nama'));

        return redirect()->route('provinsi.index')->with('success', 'Provinsi berhasil diperbarui.');
    }

    public function destroy(Provinsi $provinsi)
    {
        abort_unless(auth()->user()->hasAccess('hapus.provinsi'), 403);

        $provinsi->delete();

        return redirect()->route('provinsi.index')->with('success', 'Provinsi berhasil dihapus.');
    }
}
