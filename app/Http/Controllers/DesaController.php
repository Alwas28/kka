<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class DesaController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.desa'), 403);

        $provinsiList      = Provinsi::orderBy('nama')->get();
        $selectedProvinsi  = $request->get('provinsi_id');
        $selectedKabupaten = $request->get('kabupaten_id');
        $selectedKecamatan = $request->get('kecamatan_id');

        $kabupatenList = collect();
        $kecamatanList = collect();

        if ($selectedProvinsi) {
            $kabupatenList = Kabupaten::where('provinsi_id', $selectedProvinsi)->orderBy('nama')->get();
        }
        if ($selectedKabupaten) {
            $kecamatanList = Kecamatan::where('kabupaten_id', $selectedKabupaten)->orderBy('nama')->get();
        }

        $query = Desa::with('kecamatan.kabupaten.provinsi')->latest();

        if ($selectedKecamatan) {
            $query->where('kecamatan_id', $selectedKecamatan);
        } elseif ($selectedKabupaten) {
            $query->whereHas('kecamatan', fn($q) => $q->where('kabupaten_id', $selectedKabupaten));
        } elseif ($selectedProvinsi) {
            $query->whereHas('kecamatan.kabupaten', fn($q) => $q->where('provinsi_id', $selectedProvinsi));
        }

        $desa = $query->get();

        return view('desa.index', compact(
            'desa', 'provinsiList', 'kabupatenList', 'kecamatanList',
            'selectedProvinsi', 'selectedKabupaten', 'selectedKecamatan'
        ));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('tambah.desa'), 403);

        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'kode'         => 'required|string|max:10|unique:desa,kode',
            'nama'         => 'required|string|max:255',
        ]);

        Desa::create($request->only('kecamatan_id', 'kode', 'nama'));

        $kecamatan = Kecamatan::with('kabupaten')->find($request->kecamatan_id);

        return redirect()->route('desa.index', [
            'provinsi_id'  => $kecamatan?->kabupaten?->provinsi_id,
            'kabupaten_id' => $kecamatan?->kabupaten_id,
            'kecamatan_id' => $request->kecamatan_id,
        ])->with('success', 'Desa/Kelurahan berhasil ditambahkan.');
    }

    public function update(Request $request, Desa $desa)
    {
        abort_unless(auth()->user()->hasAccess('edit.desa'), 403);

        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'kode'         => 'required|string|max:10|unique:desa,kode,' . $desa->id,
            'nama'         => 'required|string|max:255',
        ]);

        $desa->update($request->only('kecamatan_id', 'kode', 'nama'));

        $kecamatan = Kecamatan::with('kabupaten')->find($request->kecamatan_id);

        return redirect()->route('desa.index', [
            'provinsi_id'  => $kecamatan?->kabupaten?->provinsi_id,
            'kabupaten_id' => $kecamatan?->kabupaten_id,
            'kecamatan_id' => $request->kecamatan_id,
        ])->with('success', 'Desa/Kelurahan berhasil diperbarui.');
    }

    public function json(Request $request)
    {
        $request->validate(['kecamatan_id' => 'required|exists:kecamatan,id']);

        return response()->json(
            Desa::where('kecamatan_id', $request->kecamatan_id)->orderBy('nama')->get(['id', 'nama', 'kode'])
        );
    }

    public function destroy(Desa $desa)
    {
        abort_unless(auth()->user()->hasAccess('hapus.desa'), 403);

        $kecamatanId = $desa->kecamatan_id;
        $kabupatenId = $desa->kecamatan?->kabupaten_id;
        $provinsiId  = $desa->kecamatan?->kabupaten?->provinsi_id;
        $desa->delete();

        return redirect()->route('desa.index', [
            'provinsi_id'  => $provinsiId,
            'kabupaten_id' => $kabupatenId,
            'kecamatan_id' => $kecamatanId,
        ])->with('success', 'Desa/Kelurahan berhasil dihapus.');
    }
}
