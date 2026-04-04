<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HalamanController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('lihat.halaman'), 403);

        $query = Page::with('user')->latest();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $halamanList = $query->paginate(15)->withQueryString();

        return view('halaman.index', compact('halamanList'));
    }

    public function create()
    {
        abort_unless(auth()->user()->hasAccess('tambah.halaman'), 403);
        return view('halaman.form', ['halaman' => null]);
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('tambah.halaman'), 403);

        $data = $request->validate([
            'judul'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|regex:/^[a-z0-9-]+$/|unique:pages,slug',
            'konten'           => 'required|string',
            'meta_description' => 'nullable|string|max:300',
            'gambar'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_published'     => 'nullable|boolean',
        ], [
            'judul.required'  => 'Judul halaman wajib diisi.',
            'konten.required' => 'Konten halaman wajib diisi.',
            'slug.regex'      => 'Slug hanya boleh huruf kecil, angka, dan tanda hubung.',
            'slug.unique'     => 'Slug sudah digunakan halaman lain.',
        ]);

        $data['user_id']      = Auth::id();
        $data['slug']         = $data['slug'] ?: Page::generateSlug($data['judul']);
        $data['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('halaman', 'public');
        }

        Page::create($data);

        return redirect()->route('halaman.index')->with('success', 'Halaman berhasil ditambahkan.');
    }

    public function show(Page $page)
    {
        abort_if(!$page->is_published, 404);
        return view('halaman.show', compact('page'));
    }

    public function edit(Page $page)
    {
        abort_unless(auth()->user()->hasAccess('edit.halaman'), 403);
        return view('halaman.form', ['halaman' => $page]);
    }

    public function update(Request $request, Page $page)
    {
        abort_unless(auth()->user()->hasAccess('edit.halaman'), 403);

        $data = $request->validate([
            'judul'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|regex:/^[a-z0-9-]+$/|unique:pages,slug,' . $page->id,
            'konten'           => 'required|string',
            'meta_description' => 'nullable|string|max:300',
            'gambar'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_published'     => 'nullable|boolean',
        ], [
            'judul.required'  => 'Judul halaman wajib diisi.',
            'konten.required' => 'Konten halaman wajib diisi.',
            'slug.regex'      => 'Slug hanya boleh huruf kecil, angka, dan tanda hubung.',
            'slug.unique'     => 'Slug sudah digunakan halaman lain.',
        ]);

        $data['slug']         = $data['slug'] ?: Page::generateSlug($data['judul'], $page->id);
        $data['is_published'] = $request->boolean('is_published');

        if ($request->boolean('hapus_gambar') && $page->gambar) {
            Storage::disk('public')->delete($page->gambar);
            $data['gambar'] = null;
        }

        if ($request->hasFile('gambar')) {
            if ($page->gambar) {
                Storage::disk('public')->delete($page->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('halaman', 'public');
        }

        $page->update($data);

        return redirect()->route('halaman.index')->with('success', 'Halaman berhasil diperbarui.');
    }

    public function destroy(Page $page)
    {
        abort_unless(auth()->user()->hasAccess('hapus.halaman'), 403);

        if ($page->gambar) {
            Storage::disk('public')->delete($page->gambar);
        }

        $page->delete();

        return back()->with('success', 'Halaman "' . $page->judul . '" berhasil dihapus.');
    }
}
