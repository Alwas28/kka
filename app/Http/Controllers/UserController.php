<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->hasAccess('lihat.user'), 403, 'Anda tidak memiliki akses untuk melihat halaman ini.');

        $users = User::with(['roles', 'programStudi.fakultas'])->latest()->get();
        $fakultasList = Fakultas::with('programStudi')->orderBy('nama')->get();

        return view('users.index', compact('users', 'fakultasList'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('tambah.user'), 403, 'Anda tidak memiliki akses untuk menambah data.');

        $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|max:255|unique:users,email',
            'password'           => ['required', Password::min(8)],
            'program_studi_ids'  => 'nullable|array',
            'program_studi_ids.*'=> 'exists:program_studi,id',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->programStudi()->sync($request->input('program_studi_ids', []));

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        abort_unless(auth()->user()->hasAccess('edit.user'), 403, 'Anda tidak memiliki akses untuk mengubah data.');

        $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|max:255|unique:users,email,' . $user->id,
            'password'           => ['nullable', Password::min(8)],
            'program_studi_ids'  => 'nullable|array',
            'program_studi_ids.*'=> 'exists:program_studi,id',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->programStudi()->sync($request->input('program_studi_ids', []));

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_unless(auth()->user()->hasAccess('hapus.user'), 403, 'Anda tidak memiliki akses untuk menghapus data.');

        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
