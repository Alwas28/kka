<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Naikkan level ke 6 untuk mahasiswa yang sudah masuk kelompok tapi masih level 5
        DB::statement("
            UPDATE mahasiswa
            SET mahasiswa_level_id = 6
            WHERE mahasiswa_level_id = 5
              AND id IN (SELECT mahasiswa_id FROM kelompok_mahasiswa)
        ");
    }

    public function down(): void
    {
        // Kembalikan ke 5 untuk yang tidak lagi relevan (opsional, biarkan saja)
    }
};
