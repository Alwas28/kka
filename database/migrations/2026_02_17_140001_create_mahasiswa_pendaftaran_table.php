<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->restrictOnDelete();
            $table->unique('mahasiswa_id'); // satu mahasiswa satu pendaftaran aktif

            // Identitas Diri
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat');
            $table->string('no_hp', 20);
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O', 'Tidak Tahu'])->nullable();

            // Data Akademik
            $table->unsignedTinyInteger('semester');
            $table->unsignedSmallInteger('sks_ditempuh');
            $table->decimal('ipk', 3, 2);

            // Ukuran
            $table->enum('ukuran_baju', ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL']);

            // Kesehatan
            $table->text('penyakit_diderita')->nullable();
            $table->boolean('sedang_hamil')->nullable();
            $table->text('catatan_kesehatan')->nullable();

            // Status form
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_pendaftaran');
    }
};
