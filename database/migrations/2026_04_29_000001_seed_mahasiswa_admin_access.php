<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('accesses')->insertOrIgnore([
            ['nama' => 'lihat.mahasiswa-admin', 'keterangan' => 'Lihat Data Mahasiswa (Admin)', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'edit.mahasiswa-admin',  'keterangan' => 'Edit Data Mahasiswa (Admin)',  'created_at' => now(), 'updated_at' => now()],
        ]);

        $adminRole = DB::table('roles')->where('nama', 'Administrator')->value('id');

        foreach (['lihat.mahasiswa-admin', 'edit.mahasiswa-admin'] as $nama) {
            $accessId = DB::table('accesses')->where('nama', $nama)->value('id');
            if ($adminRole && $accessId) {
                DB::table('role_access')->insertOrIgnore([
                    'role_id'   => $adminRole,
                    'access_id' => $accessId,
                ]);
            }
        }
    }

    public function down(): void
    {
        $ids = DB::table('accesses')
            ->whereIn('nama', ['lihat.mahasiswa-admin', 'edit.mahasiswa-admin'])
            ->pluck('id');

        DB::table('role_access')->whereIn('access_id', $ids)->delete();
        DB::table('accesses')->whereIn('id', $ids)->delete();
    }
};
