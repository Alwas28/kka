<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->dropColumn([
                'survey_mulai',
                'survey_selesai',
                'pendaftaran_mulai',
                'pendaftaran_selesai',
                'verifikasi_mulai',
                'verifikasi_selesai',
                'pelaksanaan_mulai',
                'pelaksanaan_selesai',
                'pelaporan_mulai',
                'pelaporan_selesai',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->date('survey_mulai')->nullable()->after('kegiatan_selesai');
            $table->date('survey_selesai')->nullable()->after('survey_mulai');
            $table->date('pendaftaran_mulai')->nullable()->after('survey_selesai');
            $table->date('pendaftaran_selesai')->nullable()->after('pendaftaran_mulai');
            $table->date('verifikasi_mulai')->nullable()->after('pendaftaran_selesai');
            $table->date('verifikasi_selesai')->nullable()->after('verifikasi_mulai');
            $table->date('pelaksanaan_mulai')->nullable()->after('verifikasi_selesai');
            $table->date('pelaksanaan_selesai')->nullable()->after('pelaksanaan_mulai');
            $table->date('pelaporan_mulai')->nullable()->after('pelaksanaan_selesai');
            $table->date('pelaporan_selesai')->nullable()->after('pelaporan_mulai');
        });
    }
};
