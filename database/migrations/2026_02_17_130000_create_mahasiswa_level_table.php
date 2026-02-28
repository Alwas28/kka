<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa_level', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->string('nama', 60);
            $table->string('keterangan', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_level');
    }
};
