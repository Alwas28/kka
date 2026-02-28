<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ganti pivot table user dengan kolom teks sederhana
        Schema::dropIfExists('survey_tim');

        Schema::table('survey_lokasi', function (Blueprint $table) {
            $table->text('tim_anggota')->nullable()->after('kegiatan_id')
                ->comment('Nama anggota tim, satu per baris');
        });
    }

    public function down(): void
    {
        Schema::table('survey_lokasi', function (Blueprint $table) {
            $table->dropColumn('tim_anggota');
        });

        Schema::create('survey_tim', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_lokasi_id')->constrained('survey_lokasi')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['survey_lokasi_id', 'user_id']);
        });
    }
};
