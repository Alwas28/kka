<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('accesses')->insertOrIgnore([
            ['nama' => 'lihat.dosen-pembimbing', 'keterangan' => 'Melihat halaman Dosen Pembimbing (kegiatan yang dibimbing)', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        DB::table('accesses')->where('nama', 'lihat.dosen-pembimbing')->delete();
    }
};
