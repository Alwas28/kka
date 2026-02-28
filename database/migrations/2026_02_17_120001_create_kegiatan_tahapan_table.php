<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatan_tahapan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->cascadeOnDelete();
            $table->enum('nama', ['survey', 'pendaftaran', 'verifikasi', 'setup_kelompok', 'pelaksanaan', 'pelaporan']);
            $table->unsignedTinyInteger('urutan')->default(0);
            $table->date('mulai')->nullable();
            $table->date('selesai')->nullable();
            $table->timestamps();

            $table->unique(['kegiatan_id', 'nama']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan_tahapan');
    }
};
