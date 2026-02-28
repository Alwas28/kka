<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()
                ->comment('Akun login pegawai (jika ada)');
            $table->string('nip', 30)->nullable()->unique()->comment('NIP / NIDN pegawai');
            $table->string('nama');
            $table->string('email')->nullable()->unique();
            $table->string('no_hp', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
