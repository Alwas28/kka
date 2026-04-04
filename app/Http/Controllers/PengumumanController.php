<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.pengumuman'), 403);

        $query = Pengumuman::with('user')->latest();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pengumumanList = $query->paginate(15)->withQueryString();

        return view('pengumuman.index', compact('pengumumanList'));
    }

    public function create()
    {
        abort_unless(auth()->user()->hasAccess('tambah.pengumuman'), 403);
        return view('pengumuman.form', ['pengumuman' => null]);
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('tambah.pengumuman'), 403);

        $data = $request->validate([
            'judul'           => 'required|string|max:255',
            'konten'          => 'required|string',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'is_penting'      => 'nullable|boolean',
            'status'          => 'required|in:aktif,tidak_aktif',
            'gambar'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'judul.required'                => 'Judul pengumuman wajib diisi.',
            'konten.required'               => 'Konten pengumuman wajib diisi.',
            'tanggal_mulai.required'        => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.after_or_equal'=> 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'gambar.image'                  => 'File gambar harus berupa gambar.',
            'gambar.max'                    => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $data['user_id']    = Auth::id();
        $data['is_penting'] = $request->boolean('is_penting');

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('pengumuman', 'public');
        }

        Pengumuman::create($data);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function show(Pengumuman $pengumuman)
    {
        return view('pengumuman.show', compact('pengumuman'));
    }

    public function edit(Pengumuman $pengumuman)
    {
        abort_unless(auth()->user()->hasAccess('edit.pengumuman'), 403);
        return view('pengumuman.form', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        abort_unless(auth()->user()->hasAccess('edit.pengumuman'), 403);

        $data = $request->validate([
            'judul'           => 'required|string|max:255',
            'konten'          => 'required|string',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'is_penting'      => 'nullable|boolean',
            'status'          => 'required|in:aktif,tidak_aktif',
            'gambar'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'judul.required'                => 'Judul pengumuman wajib diisi.',
            'konten.required'               => 'Konten pengumuman wajib diisi.',
            'tanggal_mulai.required'        => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.after_or_equal'=> 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'gambar.image'                  => 'File gambar harus berupa gambar.',
            'gambar.max'                    => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $data['is_penting'] = $request->boolean('is_penting');

        // Hapus gambar jika diminta
        if ($request->boolean('hapus_gambar') && $pengumuman->gambar) {
            Storage::disk('public')->delete($pengumuman->gambar);
            $data['gambar'] = null;
        }

        // Upload gambar baru
        if ($request->hasFile('gambar')) {
            if ($pengumuman->gambar) {
                Storage::disk('public')->delete($pengumuman->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('pengumuman', 'public');
        }

        $pengumuman->update($data);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        abort_unless(auth()->user()->hasAccess('hapus.pengumuman'), 403);

        if ($pengumuman->gambar) {
            Storage::disk('public')->delete($pengumuman->gambar);
        }

        $pengumuman->delete();

        return back()->with('success', 'Pengumuman "' . $pengumuman->judul . '" berhasil dihapus.');
    }

    public function toggleStatus(Pengumuman $pengumuman)
    {
        abort_unless(auth()->user()->hasAccess('edit.pengumuman'), 403);

        $pengumuman->update([
            'status' => $pengumuman->status === 'aktif' ? 'tidak_aktif' : 'aktif',
        ]);

        return back()->with('success', 'Status pengumuman berhasil diubah.');
    }
}
