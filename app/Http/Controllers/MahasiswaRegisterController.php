<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class MahasiswaRegisterController extends Controller
{
    public function showForm()
    {
        $programStudiList = ProgramStudi::with('fakultas')->orderBy('nama')->get();

        return view('auth.register-mahasiswa', compact('programStudiList'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'nim'              => 'required|string|max:20|unique:mahasiswa,nim',
            'nama'             => 'required|string|max:255',
            'program_studi_id' => 'required|exists:program_studi,id',
            'email'            => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:mahasiswa,email',
                'ends_with:@umkendari.ac.id',
            ],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'nim.unique'               => 'NIM sudah terdaftar.',
            'email.unique'             => 'Email sudah digunakan.',
            'email.ends_with'          => 'Email harus menggunakan domain @umkendari.ac.id.',
            'program_studi_id.required'=> 'Program studi wajib dipilih.',
            'password.confirmed'       => 'Konfirmasi password tidak cocok.',
            'password.min'             => 'Password minimal 8 karakter.',
        ]);

        Mahasiswa::create([
            'nim'                 => $request->nim,
            'nama'                => $request->nama,
            'email'               => $request->email,
            'program_studi_id'    => $request->program_studi_id,
            'mahasiswa_level_id'  => 1, // Level awal: Registrasi
            'password'            => $request->password, // auto-hashed via cast
        ]);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login menggunakan akun Anda.');
    }
}
