<?php

namespace App\Http\Controllers;

use App\Models\Access;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleAccessController extends Controller
{
    public function show(Role $role)
    {
        // Semua access, dikelompokkan berdasarkan fitur (bagian setelah titik)
        $allAccesses = Access::orderBy('nama')->get()->groupBy(function ($access) {
            $parts = explode('.', $access->nama);
            return count($parts) > 1 ? $parts[1] : $parts[0];
        });

        // ID access yang sudah dimiliki role ini
        $assignedIds = $role->accesses()->pluck('accesses.id')->toArray();

        return view('role.access', compact('role', 'allAccesses', 'assignedIds'));
    }

    public function update(Request $request, Role $role)
    {
        // Sync: replace semua access yang dipilih
        $role->accesses()->sync($request->input('access_ids', []));

        return redirect()
            ->route('role.access.show', $role)
            ->with('success', "Hak akses untuk role \"{$role->nama}\" berhasil disimpan.");
    }
}
