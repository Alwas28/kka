<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.berita'), 403);

        $query = Berita::with('user')->latest();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $beritaList = $query->paginate(15)->withQueryString();

        return view('berita.index', compact('beritaList'));
    }

    public function create()
    {
        abort_unless(auth()->user()->hasAccess('tambah.berita'), 403);
        return view('berita.form', ['berita' => null]);
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('tambah.berita'), 403);

        $data = $request->validate([
            'judul'   => 'required|string|max:255',
            'konten'  => 'required|string',
            'status'  => 'required|in:draft,published',
            'gambar'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'judul.required'  => 'Judul berita wajib diisi.',
            'konten.required' => 'Konten berita wajib diisi.',
            'gambar.image'    => 'File gambar harus berupa gambar.',
            'gambar.max'      => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $data['user_id'] = Auth::id();
        $data['slug']    = Berita::generateSlug($data['judul']);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        Berita::create($data);

        return redirect()->route('berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function show(Berita $berita)
    {
        abort_if($berita->status !== 'published', 404);
        return view('berita.show', compact('berita'));
    }

    public function edit(Berita $berita)
    {
        abort_unless(auth()->user()->hasAccess('edit.berita'), 403);
        return view('berita.form', compact('berita'));
    }

    public function update(Request $request, Berita $berita)
    {
        abort_unless(auth()->user()->hasAccess('edit.berita'), 403);

        $data = $request->validate([
            'judul'   => 'required|string|max:255',
            'konten'  => 'required|string',
            'status'  => 'required|in:draft,published',
            'gambar'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'judul.required'  => 'Judul berita wajib diisi.',
            'konten.required' => 'Konten berita wajib diisi.',
            'gambar.image'    => 'File gambar harus berupa gambar.',
            'gambar.max'      => 'Ukuran gambar maksimal 2 MB.',
        ]);

        if ($berita->judul !== $data['judul']) {
            $data['slug'] = Berita::generateSlug($data['judul'], $berita->id);
        }

        if ($request->hasFile('gambar')) {
            if ($berita->gambar) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        if ($data['status'] === 'published' && $berita->status !== 'published') {
            $data['published_at'] = now();
        }

        $berita->update($data);

        return redirect()->route('berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Upload gambar dari dalam konten TinyMCE editor.
     * Mengembalikan JSON {location: url} sesuai format TinyMCE.
     */
    public function uploadGambarKonten(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ], [
            'file.image' => 'File harus berupa gambar.',
            'file.max'   => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $path = $request->file('file')->store('konten-gambar', 'public');

        return response()->json([
            'location' => Storage::url($path),
        ]);
    }

    public function destroy(Berita $berita)
    {
        abort_unless(auth()->user()->hasAccess('hapus.berita'), 403);

        if ($berita->gambar) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();

        return back()->with('success', 'Berita "' . $berita->judul . '" berhasil dihapus.');
    }
}
