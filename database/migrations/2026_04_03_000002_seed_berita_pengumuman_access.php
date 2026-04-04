<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $accesses = [
            ['nama' => 'lihat.berita',      'keterangan' => 'Melihat daftar berita'],
            ['nama' => 'tambah.berita',     'keterangan' => 'Menambah berita baru'],
            ['nama' => 'edit.berita',       'keterangan' => 'Mengedit berita'],
            ['nama' => 'hapus.berita',      'keterangan' => 'Menghapus berita'],
            ['nama' => 'lihat.pengumuman',  'keterangan' => 'Melihat daftar pengumuman'],
            ['nama' => 'tambah.pengumuman', 'keterangan' => 'Menambah pengumuman baru'],
            ['nama' => 'edit.pengumuman',   'keterangan' => 'Mengedit pengumuman'],
            ['nama' => 'hapus.pengumuman',  'keterangan' => 'Menghapus pengumuman'],
        ];

        foreach ($accesses as $access) {
            DB::table('accesses')->updateOrInsert(
                ['nama' => $access['nama']],
                array_merge($access, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // Berikan semua akses berita & pengumuman ke role Administrator dan Panitia
        $adminRole  = DB::table('roles')->where('nama', 'Administrator')->value('id');
        $panitiaRole = DB::table('roles')->where('nama', 'Panitia')->value('id');

        $newAccessIds = DB::table('accesses')
            ->whereIn('nama', array_column($accesses, 'nama'))
            ->pluck('id');

        foreach ($newAccessIds as $accessId) {
            foreach (array_filter([$adminRole, $panitiaRole]) as $roleId) {
                DB::table('role_access')->updateOrInsert(
                    ['role_id' => $roleId, 'access_id' => $accessId]
                );
            }
        }
    }

    public function down(): void
    {
        $names = [
            'lihat.berita', 'tambah.berita', 'edit.berita', 'hapus.berita',
            'lihat.pengumuman', 'tambah.pengumuman', 'edit.pengumuman', 'hapus.pengumuman',
        ];

        $ids = DB::table('accesses')->whereIn('nama', $names)->pluck('id');
        DB::table('role_access')->whereIn('access_id', $ids)->delete();
        DB::table('accesses')->whereIn('nama', $names)->delete();
    }
};
