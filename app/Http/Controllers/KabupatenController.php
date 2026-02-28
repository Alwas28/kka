<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class KabupatenController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.kabupaten'), 403);

        $provinsiList     = Provinsi::orderBy('nama')->get();
        $selectedProvinsi = $request->get('provinsi_id');

        $query = Kabupaten::with('provinsi')->withCount('kecamatan')->latest();

        if ($selectedProvinsi) {
            $query->where('provinsi_id', $selectedProvinsi);
        }

        $kabupaten = $query->get();

        return view('kabupaten.index', compact('kabupaten', 'provinsiList', 'selectedProvinsi'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('tambah.kabupaten'), 403);

        $request->validate([
            'provinsi_id' => 'required|exists:provinsi,id',
            'kode'        => 'required|string|max:10|unique:kabupaten,kode',
            'nama'        => 'required|string|max:255',
        ]);

        Kabupaten::create($request->only('provinsi_id', 'kode', 'nama'));

        return redirect()->route('kabupaten.index', ['provinsi_id' => $request->provinsi_id])
            ->with('success', 'Kabupaten/Kota berhasil ditambahkan.');
    }

    public function update(Request $request, Kabupaten $kabupaten)
    {
        abort_unless(auth()->user()->hasAccess('edit.kabupaten'), 403);

        $request->validate([
            'provinsi_id' => 'required|exists:provinsi,id',
            'kode'        => 'required|string|max:10|unique:kabupaten,kode,' . $kabupaten->id,
            'nama'        => 'required|string|max:255',
        ]);

        $kabupaten->update($request->only('provinsi_id', 'kode', 'nama'));

        return redirect()->route('kabupaten.index', ['provinsi_id' => $request->provinsi_id])
            ->with('success', 'Kabupaten/Kota berhasil diperbarui.');
    }

    public function destroy(Kabupaten $kabupaten)
    {
        abort_unless(auth()->user()->hasAccess('hapus.kabupaten'), 403);

        $provinsiId = $kabupaten->provinsi_id;
        $kabupaten->delete();

        return redirect()->route('kabupaten.index', ['provinsi_id' => $provinsiId])
            ->with('success', 'Kabupaten/Kota berhasil dihapus.');
    }

    public function json(Request $request)
    {
        $data = Kabupaten::where('provinsi_id', $request->provinsi_id)
            ->orderBy('nama')
            ->get(['id', 'kode', 'nama']);

        return response()->json($data);
    }
}
