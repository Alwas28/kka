<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->hasAccess('lihat.periode'), 403, 'Anda tidak memiliki akses untuk melihat halaman ini.');

        $periodeList = Periode::latest()->get();

        return view('periode.index', compact('periodeList'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('tambah.periode'), 403, 'Anda tidak memiliki akses untuk menambah data.');

        $request->validate([
            'nama'       => 'required|string|max:255|unique:periode,nama',
            'keterangan' => 'nullable|string|max:255',
        ]);

        Periode::create($request->only('nama', 'keterangan'));

        return redirect()->route('periode.index')->with('success', 'Periode berhasil ditambahkan.');
    }

    public function update(Request $request, Periode $periode)
    {
        abort_unless(auth()->user()->hasAccess('edit.periode'), 403, 'Anda tidak memiliki akses untuk mengubah data.');

        $request->validate([
            'nama'       => 'required|string|max:255|unique:periode,nama,' . $periode->id,
            'keterangan' => 'nullable|string|max:255',
        ]);

        $periode->update($request->only('nama', 'keterangan'));

        return redirect()->route('periode.index')->with('success', 'Periode berhasil diperbarui.');
    }

    public function destroy(Periode $periode)
    {
        abort_unless(auth()->user()->hasAccess('hapus.periode'), 403, 'Anda tidak memiliki akses untuk menghapus data.');

        $periode->delete();

        return redirect()->route('periode.index')->with('success', 'Periode berhasil dihapus.');
    }
}
