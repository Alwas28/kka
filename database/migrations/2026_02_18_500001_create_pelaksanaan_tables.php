<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah toggle ke kegiatan
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->boolean('logbook_aktif')->default(false)->after('nama');
            $table->boolean('laporan_aktif')->default(false)->after('logbook_aktif');
        });

        // Logbook harian mahasiswa
        Schema::create('logbook', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('survey_lokasi_id')->constrained('survey_lokasi')->cascadeOnDelete();
            $table->date('tanggal');
            $table->text('kegiatan_dilakukan');
            $table->string('lokasi')->nullable();
            $table->timestamps();
        });

        // Laporan individu (file upload)
        Schema::create('laporan_individu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('survey_lokasi_id')->constrained('survey_lokasi')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'survey_lokasi_id']);
        });

        // Laporan akhir kelompok (hanya koordinator)
        Schema::create('laporan_akhir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_lokasi_id')->constrained('survey_lokasi')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete()
                ->comment('Koordinator yang mengupload');
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->unique('survey_lokasi_id');
        });

        // Nilai dari DPL
        Schema::create('nilai_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('survey_lokasi_id')->constrained('survey_lokasi')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawai')->nullOnDelete()
                ->comment('DPL yang memberikan nilai');
            $table->decimal('nilai_logbook', 5, 2)->nullable();
            $table->decimal('nilai_laporan_individu', 5, 2)->nullable();
            $table->decimal('nilai_laporan_akhir', 5, 2)->nullable();
            $table->decimal('nilai_akhir', 5, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'survey_lokasi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_mahasiswa');
        Schema::dropIfExists('laporan_akhir');
        Schema::dropIfExists('laporan_individu');
        Schema::dropIfExists('logbook');
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->dropColumn(['logbook_aktif', 'laporan_aktif']);
        });
    }
};
