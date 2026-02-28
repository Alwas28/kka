<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatan_komponen_penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->cascadeOnDelete();
            $table->string('nama');
            $table->unsignedTinyInteger('persentase'); // 0–100
            $table->unsignedTinyInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('kegiatan_grade', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->cascadeOnDelete();
            $table->string('grade', 5);          // A, A-, B+, B, dst.
            $table->decimal('nilai_min', 5, 2);  // 0.00 – 100.00
            $table->decimal('nilai_max', 5, 2);
            $table->unsignedTinyInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan_grade');
        Schema::dropIfExists('kegiatan_komponen_penilaian');
    }
};
