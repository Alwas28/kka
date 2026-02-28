<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom jika belum ada
        if (! Schema::hasColumn('mahasiswa', 'mahasiswa_level_id')) {
            Schema::table('mahasiswa', function (Blueprint $table) {
                $table->unsignedTinyInteger('mahasiswa_level_id')
                      ->default(1)
                      ->after('program_studi_id');
            });
        }

        // Tambah foreign key jika belum ada
        $fkExists = collect(Schema::getIndexes('mahasiswa'))
            ->contains(fn ($idx) => str_contains($idx['name'] ?? '', 'mahasiswa_level_id'));

        if (! $fkExists) {
            Schema::table('mahasiswa', function (Blueprint $table) {
                $table->foreign('mahasiswa_level_id')
                      ->references('id')
                      ->on('mahasiswa_level')
                      ->restrictOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropForeign(['mahasiswa_level_id']);
            $table->dropColumn('mahasiswa_level_id');
        });
    }
};
