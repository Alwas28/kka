<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.kecamatan'), 403);

        $provinsiList      = Provinsi::orderBy('nama')->get();
        $selectedProvinsi  = $request->get('provinsi_id');
        $selectedKabupaten = $request->get('kabupaten_id');

        $kabupatenList = collect();
        if ($selectedProvinsi) {
            $kabupatenList = Kabupaten::where('provinsi_id', $selectedProvinsi)->orderBy('nama')->get();
        }

        $query = Kecamatan::with('kabupaten.provinsi')->withCount('desa')->latest();

        if ($selectedKabupaten) {
            $query->where('kabupaten_id', $selectedKabupaten);
        } elseif ($selectedProvinsi) {
            $query->whereHas('kabupaten', fn($q) => $q->where('provinsi_id', $selectedProvinsi));
        }

        $kecamatan = $query->get();

        return view('kecamatan.index', compact(
            'kecamatan', 'provinsiList', 'kabupatenList',
            'selectedProvinsi', 'selectedKabupaten'
        ));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('tambah.kecamatan'), 403);

        $request->validate([
            'kabupaten_id' => 'required|exists:kabupaten,id',
            'kode'         => 'required|string|max:10|unique:kecamatan,kode',
            'nama'         => 'required|string|max:255',
        ]);

        Kecamatan::create($request->only('kabupaten_id', 'kode', 'nama'));

        $kabupaten = Kabupaten::find($request->kabupaten_id);

        return redirect()->route('kecamatan.index', [
            'provinsi_id'  => $kabupaten?->provinsi_id,
            'kabupaten_id' => $request->kabupaten_id,
        ])->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    public function update(Request $request, Kecamatan $kecamatan)
    {
        abort_unless(auth()->user()->hasAccess('edit.kecamatan'), 403);

        $request->validate([
            'kabupaten_id' => 'required|exists:kabupaten,id',
            'kode'         => 'required|string|max:10|unique:kecamatan,kode,' . $kecamatan->id,
            'nama'         => 'required|string|max:255',
        ]);

        $kecamatan->update($request->only('kabupaten_id', 'kode', 'nama'));

        $kabupaten = Kabupaten::find($request->kabupaten_id);

        return redirect()->route('kecamatan.index', [
            'provinsi_id'  => $kabupaten?->provinsi_id,
            'kabupaten_id' => $request->kabupaten_id,
        ])->with('success', 'Kecamatan berhasil diperbarui.');
    }

    public function destroy(Kecamatan $kecamatan)
    {
        abort_unless(auth()->user()->hasAccess('hapus.kecamatan'), 403);

        $kabupatenId = $kecamatan->kabupaten_id;
        $provinsiId  = $kecamatan->kabupaten?->provinsi_id;
        $kecamatan->delete();

        return redirect()->route('kecamatan.index', [
            'provinsi_id'  => $provinsiId,
            'kabupaten_id' => $kabupatenId,
        ])->with('success', 'Kecamatan berhasil dihapus.');
    }

    public function json(Request $request)
    {
        $data = Kecamatan::where('kabupaten_id', $request->kabupaten_id)
            ->orderBy('nama')
            ->get(['id', 'kode', 'nama']);

        return response()->json($data);
    }
}
