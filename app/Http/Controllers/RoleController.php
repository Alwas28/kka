<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest()->get();
        return view('role.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:255|unique:roles,nama',
            'keterangan' => 'nullable|string|max:255',
        ]);

        Role::create($request->only('nama', 'keterangan'));

        return redirect()->route('role.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'nama'       => 'required|string|max:255|unique:roles,nama,' . $role->id,
            'keterangan' => 'nullable|string|max:255',
        ]);

        $role->update($request->only('nama', 'keterangan'));

        return redirect()->route('role.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('role.index')->with('success', 'Role berhasil dihapus.');
    }
}
