<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_lokasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desa')->cascadeOnDelete();
            $table->foreignId('surveyor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('kegiatan_id')->nullable()->constrained('kegiatan')->nullOnDelete();
            $table->enum('status', ['belum_survey', 'sudah_survey', 'disetujui', 'ditolak'])->default('belum_survey');

            // Hasil Survey (diisi surveyor)
            $table->string('nama_kades')->nullable();
            $table->string('no_hp_kades')->nullable();
            $table->string('pemberi_informasi')->nullable();
            $table->enum('rencana_posko', ['rumah_kades', 'rumah_warga', 'lainnya'])->nullable();
            $table->string('rencana_posko_lainnya')->nullable();
            $table->text('kondisi_air')->nullable();
            $table->text('kondisi_listrik')->nullable();
            $table->text('kondisi_transportasi')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('gmaps_url')->nullable();
            $table->boolean('rekomendasi')->nullable();
            $table->text('alasan_rekomendasi')->nullable();

            // Persetujuan Panitia
            $table->boolean('disetujui')->nullable();
            $table->text('catatan_panitia')->nullable();
            $table->timestamp('surveyed_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_lokasi');
    }
};
