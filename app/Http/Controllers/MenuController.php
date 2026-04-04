<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->hasAccess('lihat.menu'), 403);

        $menus = Menu::whereNull('parent_id')
            ->orderBy('urutan')
            ->with(['children' => fn($q) => $q->orderBy('urutan')])
            ->get();

        return view('menu.index', compact('menus'));
    }

    public function create()
    {
        abort_unless(auth()->user()->hasAccess('tambah.menu'), 403);

        $parents = Menu::whereNull('parent_id')->orderBy('urutan')->get();
        return view('menu.form', ['menu' => null, 'parents' => $parents]);
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->hasAccess('tambah.menu'), 403);

        $data = $request->validate([
            'label'     => 'required|string|max:100',
            'url'       => 'nullable|string|max:500',
            'icon'      => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:menus,id',
            'urutan'    => 'nullable|integer|min:0',
            'target'    => 'required|in:_self,_blank',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['urutan']    = $data['urutan'] ?? 0;

        Menu::create($data);

        return redirect()->route('menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        abort_unless(auth()->user()->hasAccess('edit.menu'), 403);

        // Hanya top-level yang bisa jadi parent (tidak boleh circular)
        $parents = Menu::whereNull('parent_id')->where('id', '!=', $menu->id)->orderBy('urutan')->get();
        return view('menu.form', compact('menu', 'parents'));
    }

    public function update(Request $request, Menu $menu)
    {
        abort_unless(auth()->user()->hasAccess('edit.menu'), 403);

        $data = $request->validate([
            'label'     => 'required|string|max:100',
            'url'       => 'nullable|string|max:500',
            'icon'      => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:menus,id',
            'urutan'    => 'nullable|integer|min:0',
            'target'    => 'required|in:_self,_blank',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['urutan']    = $data['urutan'] ?? 0;

        // Cegah menu jadi parent dirinya sendiri
        if (isset($data['parent_id']) && $data['parent_id'] == $menu->id) {
            $data['parent_id'] = null;
        }

        $menu->update($data);

        return redirect()->route('menu.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        abort_unless(auth()->user()->hasAccess('hapus.menu'), 403);

        $menu->delete(); // children terhapus otomatis (cascade)

        return back()->with('success', 'Menu "' . $menu->label . '" berhasil dihapus.');
    }
}
