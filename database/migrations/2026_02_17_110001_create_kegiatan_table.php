<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_kka_id')->constrained('jenis_kka')->cascadeOnDelete();
            $table->foreignId('tahun_id')->constrained('tahun')->cascadeOnDelete();
            $table->foreignId('periode_id')->constrained('periode')->cascadeOnDelete();
            $table->string('nama');

            // Timeline
            $table->date('kegiatan_mulai');
            $table->date('kegiatan_selesai');

            $table->date('survey_mulai')->nullable();
            $table->date('survey_selesai')->nullable();

            $table->date('pendaftaran_mulai');
            $table->date('pendaftaran_selesai');

            $table->date('verifikasi_mulai')->nullable();
            $table->date('verifikasi_selesai')->nullable();

            $table->date('pelaksanaan_mulai');
            $table->date('pelaksanaan_selesai');

            $table->date('pelaporan_mulai')->nullable();
            $table->date('pelaporan_selesai')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};
