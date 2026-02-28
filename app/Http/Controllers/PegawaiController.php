<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.pegawai'), 403);

        $query = Pegawai::with('user')->orderBy('nama');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->where('nama', 'like', "%{$q}%")
                   ->orWhere('nip', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        if ($request->filled('akun')) {
            if ($request->akun === 'ada') {
                $query->whereNotNull('user_id');
            } else {
                $query->whereNull('user_id');
            }
        }

        $pegawai = $query->get();

        return view('pegawai.index', compact('pegawai'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('tambah.pegawai'), 403);

        $request->validate([
            'nip'      => 'nullable|string|max:30|unique:pegawai,nip',
            'nama'     => 'required|string|max:255',
            'email'    => 'nullable|email|max:255|unique:pegawai,email',
            'no_hp'    => 'nullable|string|max:20',
            'password' => ['nullable', Password::min(8)],
        ]);

        // Jika password diisi, buat akun user terlebih dahulu
        $userId = null;
        if ($request->filled('password')) {
            $emailAkun = $request->email;
            abort_if(!$emailAkun, 422, 'Email wajib diisi jika ingin membuat akun login.');
            abort_if(User::where('email', $emailAkun)->exists(), 422, 'Email sudah digunakan sebagai akun user.');

            $user   = User::create([
                'name'     => $request->nama,
                'email'    => $emailAkun,
                'password' => Hash::make($request->password),
            ]);
            $userId = $user->id;
        }

        Pegawai::create([
            'user_id'   => $userId,
            'nip'       => $request->nip ?: null,
            'nama'      => $request->nama,
            'email'     => $request->email ?: null,
            'no_hp'     => $request->no_hp ?: null,
            'is_active' => true,
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        abort_unless(auth()->user()->hasAccess('edit.pegawai'), 403);

        $request->validate([
            'nip'      => 'nullable|string|max:30|unique:pegawai,nip,' . $pegawai->id,
            'nama'     => 'required|string|max:255',
            'email'    => 'nullable|email|max:255|unique:pegawai,email,' . $pegawai->id,
            'no_hp'    => 'nullable|string|max:20',
            'password' => ['nullable', Password::min(8)],
        ]);

        $pegawai->update([
            'nip'       => $request->nip ?: null,
            'nama'      => $request->nama,
            'email'     => $request->email ?: null,
            'no_hp'     => $request->no_hp ?: null,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Perbarui nama/password di akun user jika ada
        if ($pegawai->user) {
            $updateUser = ['name' => $request->nama];
            if ($request->filled('password')) {
                $updateUser['password'] = Hash::make($request->password);
            }
            $pegawai->user->update($updateUser);
        }

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        abort_unless(auth()->user()->hasAccess('hapus.pegawai'), 403);

        $pegawai->delete();
        // User account tidak ikut dihapus — hanya unlink via nullOnDelete di FK

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
    }

    /**
     * Buat akun login untuk pegawai yang belum memiliki akun.
     */
    public function buatAkun(Request $request, Pegawai $pegawai)
    {
        abort_unless(auth()->user()->hasAccess('edit.pegawai'), 403);
        abort_if($pegawai->hasAccount(), 422, 'Pegawai ini sudah memiliki akun login.');

        $request->validate([
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => ['required', Password::min(8)],
        ]);

        $user = User::create([
            'name'     => $pegawai->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $pegawai->update(['user_id' => $user->id]);

        // Sinkronkan email pegawai jika belum diisi
        if (!$pegawai->email) {
            $pegawai->update(['email' => $request->email]);
        }

        return redirect()->route('pegawai.index')->with('success', "Akun login untuk {$pegawai->nama} berhasil dibuat.");
    }
}
