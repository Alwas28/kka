<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            // Hapus FK program_studi_id jika ada
            if (Schema::hasColumn('pegawai', 'program_studi_id')) {
                $table->dropConstrainedForeignId('program_studi_id');
            }

            // Hapus kolom jabatan jika ada
            if (Schema::hasColumn('pegawai', 'jabatan')) {
                $table->dropColumn('jabatan');
            }

            // Tambah user_id (FK ke users, nullable)
            if (!Schema::hasColumn('pegawai', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()
                    ->comment('Akun login pegawai (jika ada)')
                    ->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            if (Schema::hasColumn('pegawai', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }

            if (!Schema::hasColumn('pegawai', 'jabatan')) {
                $table->string('jabatan')->after('no_hp');
            }

            if (!Schema::hasColumn('pegawai', 'program_studi_id')) {
                $table->foreignId('program_studi_id')->nullable()->constrained('program_studi')->nullOnDelete()->after('jabatan');
            }
        });
    }
};
