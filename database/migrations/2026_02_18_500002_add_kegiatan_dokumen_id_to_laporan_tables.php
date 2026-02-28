<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── laporan_individu ──────────────────────────────────────────────────
        // Tambah kolom + FK jika belum ada (mungkin sudah ada dari run sebelumnya)
        if (!Schema::hasColumn('laporan_individu', 'kegiatan_dokumen_id')) {
            Schema::table('laporan_individu', function (Blueprint $table) {
                $table->foreignId('kegiatan_dokumen_id')
                      ->nullable()
                      ->after('survey_lokasi_id')
                      ->constrained('kegiatan_dokumen')
                      ->nullOnDelete();
            });
        }

        // Tambah unique baru (mencakup mahasiswa_id di posisi pertama,
        // sehingga FK pada mahasiswa_id tetap terdukung saat drop unique lama)
        $newUniqExists = collect(DB::select('SHOW INDEX FROM laporan_individu'))
            ->contains('Key_name', 'lap_ind_mhs_lok_dok_unique');

        if (!$newUniqExists) {
            Schema::table('laporan_individu', function (Blueprint $table) {
                $table->unique(
                    ['mahasiswa_id', 'survey_lokasi_id', 'kegiatan_dokumen_id'],
                    'lap_ind_mhs_lok_dok_unique'
                );
            });
        }

        // Sekarang aman drop unique lama (FK sudah terdukung oleh unique baru)
        $oldUniqExists = collect(DB::select('SHOW INDEX FROM laporan_individu'))
            ->contains('Key_name', 'laporan_individu_mahasiswa_id_survey_lokasi_id_unique');

        if ($oldUniqExists) {
            Schema::table('laporan_individu', function (Blueprint $table) {
                $table->dropUnique('laporan_individu_mahasiswa_id_survey_lokasi_id_unique');
            });
        }

        // ── laporan_akhir ─────────────────────────────────────────────────────
        if (!Schema::hasColumn('laporan_akhir', 'kegiatan_dokumen_id')) {
            Schema::table('laporan_akhir', function (Blueprint $table) {
                $table->foreignId('kegiatan_dokumen_id')
                      ->nullable()
                      ->after('survey_lokasi_id')
                      ->constrained('kegiatan_dokumen')
                      ->nullOnDelete();
            });
        }

        $newUniqAkhirExists = collect(DB::select('SHOW INDEX FROM laporan_akhir'))
            ->contains('Key_name', 'lap_akhir_lok_dok_unique');

        if (!$newUniqAkhirExists) {
            Schema::table('laporan_akhir', function (Blueprint $table) {
                $table->unique(
                    ['survey_lokasi_id', 'kegiatan_dokumen_id'],
                    'lap_akhir_lok_dok_unique'
                );
            });
        }

        $oldUniqAkhirExists = collect(DB::select('SHOW INDEX FROM laporan_akhir'))
            ->contains('Key_name', 'laporan_akhir_survey_lokasi_id_unique');

        if ($oldUniqAkhirExists) {
            Schema::table('laporan_akhir', function (Blueprint $table) {
                $table->dropUnique('laporan_akhir_survey_lokasi_id_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::table('laporan_akhir', function (Blueprint $table) {
            $table->unique('survey_lokasi_id');
            $table->dropUnique('lap_akhir_lok_dok_unique');
            $table->dropForeign(['kegiatan_dokumen_id']);
            $table->dropColumn('kegiatan_dokumen_id');
        });

        Schema::table('laporan_individu', function (Blueprint $table) {
            $table->unique(['mahasiswa_id', 'survey_lokasi_id']);
            $table->dropUnique('lap_ind_mhs_lok_dok_unique');
            $table->dropForeign(['kegiatan_dokumen_id']);
            $table->dropColumn('kegiatan_dokumen_id');
        });
    }
};
