<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswa_dokumen', function (Blueprint $table) {
            $table->enum('status', ['pending', 'diterima', 'ditolak'])
                  ->default('pending')
                  ->after('file_size');
            $table->text('catatan_verifikasi')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa_dokumen', function (Blueprint $table) {
            $table->dropColumn(['status', 'catatan_verifikasi']);
        });
    }
};
