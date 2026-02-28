<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['id' => 1, 'nama' => 'Registrasi',         'keterangan' => 'Menunggu persetujuan prodi.'],
            ['id' => 2, 'nama' => 'Disetujui Prodi',     'keterangan' => 'Lengkapi persyaratan.'],
            ['id' => 3, 'nama' => 'Submit Pendaftaran',  'keterangan' => 'Menunggu verifikasi dokumen.'],
            ['id' => 4, 'nama' => 'Perbaikan Dokumen',   'keterangan' => 'Beberapa dokumen perlu diperbaiki.'],
            ['id' => 5, 'nama' => 'Disetujui Panitia',   'keterangan' => 'Anda telah terdaftar. Silakan menunggu pembagian kelompok.'],
            ['id' => 6, 'nama' => 'Pelaksanaan',          'keterangan' => 'KKA sedang berlangsung.'],
            ['id' => 7, 'nama' => 'Selesai',             'keterangan' => 'Kegiatan KKA telah selesai.'],
        ];

        $now = now();
        foreach ($levels as &$row) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        DB::table('mahasiswa_level')->insertOrIgnore($levels);
    }
}
