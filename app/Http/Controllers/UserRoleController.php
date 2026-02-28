<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->get();
        $roles = Role::orderBy('nama')->get();

        return view('user-role.index', compact('users', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role_ids'   => 'nullable|array',
            'role_ids.*' => 'exists:roles,id',
        ]);

        $user->roles()->sync($request->input('role_ids', []));

        return redirect()
            ->route('user-role.index')
            ->with('success', "Role untuk user \"{$user->name}\" berhasil diperbarui.");
    }
}
