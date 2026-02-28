<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa_notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')
                  ->constrained('mahasiswa')
                  ->cascadeOnDelete();
            $table->string('judul');
            $table->text('pesan');
            $table->string('ikon')->default('fa-bell');
            $table->string('warna')->default('#6b7280');
            $table->string('url')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['mahasiswa_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_notifikasi');
    }
};
