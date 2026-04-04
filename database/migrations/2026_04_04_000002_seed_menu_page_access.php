<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $accesses = [
            ['nama' => 'lihat.menu',     'keterangan' => 'Lihat daftar menu navigasi'],
            ['nama' => 'tambah.menu',    'keterangan' => 'Tambah menu navigasi baru'],
            ['nama' => 'edit.menu',      'keterangan' => 'Edit menu navigasi'],
            ['nama' => 'hapus.menu',     'keterangan' => 'Hapus menu navigasi'],
            ['nama' => 'lihat.halaman',  'keterangan' => 'Lihat daftar halaman konten'],
            ['nama' => 'tambah.halaman', 'keterangan' => 'Tambah halaman konten baru'],
            ['nama' => 'edit.halaman',   'keterangan' => 'Edit halaman konten'],
            ['nama' => 'hapus.halaman',  'keterangan' => 'Hapus halaman konten'],
        ];

        foreach ($accesses as $acc) {
            DB::table('accesses')->updateOrInsert(
                ['nama' => $acc['nama']],
                ['nama' => $acc['nama'], 'keterangan' => $acc['keterangan']]
            );
        }

        // Assign ke role Administrator (id=1) dan Panitia (id=2)
        $roles = DB::table('roles')->whereIn('nama', ['Administrator', 'Panitia'])->pluck('id');
        foreach ($roles as $roleId) {
            foreach ($accesses as $acc) {
                $accessId = DB::table('accesses')->where('nama', $acc['nama'])->value('id');
                if ($accessId) {
                    DB::table('role_access')->updateOrInsert(
                        ['role_id' => $roleId, 'access_id' => $accessId]
                    );
                }
            }
        }
    }

    public function down(): void
    {
        $names = ['lihat.menu','tambah.menu','edit.menu','hapus.menu',
                  'lihat.halaman','tambah.halaman','edit.halaman','hapus.halaman'];
        DB::table('accesses')->whereIn('nama', $names)->delete();
    }
};
