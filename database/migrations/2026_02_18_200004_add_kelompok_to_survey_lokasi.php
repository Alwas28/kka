<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('survey_lokasi', function (Blueprint $table) {
            $table->unsignedSmallInteger('kelompok')->nullable()->after('tim_anggota')
                ->comment('Nomor kelompok KKA yang ditugaskan ke lokasi ini');
        });
    }

    public function down(): void
    {
        Schema::table('survey_lokasi', function (Blueprint $table) {
            $table->dropColumn('kelompok');
        });
    }
};
