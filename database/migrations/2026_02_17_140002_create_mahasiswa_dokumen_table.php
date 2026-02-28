<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('mahasiswa_dokumen');

        Schema::create('mahasiswa_dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_pendaftaran_id')
                  ->constrained('mahasiswa_pendaftaran')
                  ->cascadeOnDelete();
            $table->foreignId('kegiatan_dokumen_id')
                  ->constrained('kegiatan_dokumen')
                  ->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedInteger('file_size')->comment('bytes');
            $table->timestamps();

            $table->unique(['mahasiswa_pendaftaran_id', 'kegiatan_dokumen_id'], 'mhs_dok_pend_keg_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_dokumen');
    }
};
