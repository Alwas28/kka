<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel baru: nilai per komponen penilaian (dinamis)
        Schema::create('nilai_komponen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')
                ->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('survey_lokasi_id')
                ->constrained('survey_lokasi')->cascadeOnDelete();
            $table->foreignId('kegiatan_komponen_penilaian_id')
                ->constrained('kegiatan_komponen_penilaian')->cascadeOnDelete();
            $table->decimal('nilai', 5, 2)->nullable();
            $table->timestamps();

            $table->unique(
                ['mahasiswa_id', 'survey_lokasi_id', 'kegiatan_komponen_penilaian_id'],
                'nilai_komponen_unique'
            );
        });

        // Hapus 3 kolom tetap dari nilai_mahasiswa — diganti oleh nilai_komponen
        Schema::table('nilai_mahasiswa', function (Blueprint $table) {
            $table->dropColumn([
                'nilai_logbook',
                'nilai_laporan_individu',
                'nilai_laporan_akhir',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_komponen');

        Schema::table('nilai_mahasiswa', function (Blueprint $table) {
            $table->decimal('nilai_logbook',          5, 2)->nullable()->after('pegawai_id');
            $table->decimal('nilai_laporan_individu', 5, 2)->nullable()->after('nilai_logbook');
            $table->decimal('nilai_laporan_akhir',    5, 2)->nullable()->after('nilai_laporan_individu');
        });
    }
};
