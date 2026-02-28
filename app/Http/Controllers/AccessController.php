<?php

namespace App\Http\Controllers;

use App\Models\Access;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function index()
    {
        $accesses = Access::latest()->get();
        return view('access.index', compact('accesses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:accesses,nama',
            'keterangan' => 'nullable|string|max:255',
        ]);

        Access::create($request->only('nama', 'keterangan'));

        return redirect()->route('access.index')->with('success', 'Access berhasil ditambahkan.');
    }

    public function update(Request $request, Access $access)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:accesses,nama,' . $access->id,
            'keterangan' => 'nullable|string|max:255',
        ]);

        $access->update($request->only('nama', 'keterangan'));

        return redirect()->route('access.index')->with('success', 'Access berhasil diperbarui.');
    }

    public function destroy(Access $access)
    {
        $access->delete();

        return redirect()->route('access.index')->with('success', 'Access berhasil dihapus.');
    }
}
