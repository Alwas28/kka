<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Peserta (mahasiswa) per kelompok/survey_lokasi
        Schema::create('kelompok_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_lokasi_id')->constrained('survey_lokasi')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->boolean('is_koordinator')->default(false);
            $table->timestamps();

            $table->unique(['survey_lokasi_id', 'mahasiswa_id']);
        });

        // Dosen Pembimbing Lapangan per kelompok/survey_lokasi
        Schema::create('kelompok_dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_lokasi_id')->constrained('survey_lokasi')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['survey_lokasi_id', 'pegawai_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelompok_dosen');
        Schema::dropIfExists('kelompok_mahasiswa');
    }
};
