<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('role_access')->truncate();
        DB::table('user_role')->truncate();
        DB::table('users')->truncate();
        DB::table('accesses')->truncate();
        DB::table('roles')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ─── ROLES ───────────────────────────────────────────────
        DB::table('roles')->insert([
            ['id' => 1, 'nama' => 'Administrator',  'keterangan' => 'Administrator',             'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama' => 'Panitia',        'keterangan' => 'Panitia Kegiatan',          'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama' => 'Program Studi',  'keterangan' => 'Prodi',                     'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama' => 'TIM Survey',     'keterangan' => 'TIM Survey Lokasi KKA',     'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nama' => 'DPL',            'keterangan' => 'Dosen Pembimbing Lapangan', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'nama' => 'btq',            'keterangan' => 'Baca Tulis Alquran',        'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'nama' => 'Keuangan',       'keterangan' => 'Verifikasi Keuangan',       'created_at' => now(), 'updated_at' => now()],
        ]);

        // ─── ACCESSES ─────────────────────────────────────────────
        DB::table('accesses')->insert([
            ['id' =>  1, 'nama' => 'lihat.user',                    'keterangan' => 'Lihat User',                                           'created_at' => now(), 'updated_at' => now()],
            ['id' =>  2, 'nama' => 'tambah.user',                   'keterangan' => 'Tambah User',                                          'created_at' => now(), 'updated_at' => now()],
            ['id' =>  3, 'nama' => 'edit.user',                     'keterangan' => 'Edit User',                                            'created_at' => now(), 'updated_at' => now()],
            ['id' =>  4, 'nama' => 'hapus.user',                    'keterangan' => 'Hapus User',                                           'created_at' => now(), 'updated_at' => now()],
            ['id' =>  5, 'nama' => 'lihat.role',                    'keterangan' => 'Lihat Role',                                           'created_at' => now(), 'updated_at' => now()],
            ['id' =>  6, 'nama' => 'tambah.role',                   'keterangan' => 'Tambah Role',                                          'created_at' => now(), 'updated_at' => now()],
            ['id' =>  7, 'nama' => 'edit.role',                     'keterangan' => 'Edit Role',                                            'created_at' => now(), 'updated_at' => now()],
            ['id' =>  8, 'nama' => 'hapus.role',                    'keterangan' => 'Hapus Role',                                           'created_at' => now(), 'updated_at' => now()],
            ['id' =>  9, 'nama' => 'lihat.role-user',               'keterangan' => 'Lihat Role User',                                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'nama' => 'tambah.role-user',              'keterangan' => 'Tambah Role User',                                     'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'nama' => 'edit.role-user',                'keterangan' => 'Edit Role User',                                       'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'nama' => 'hapus.role-user',               'keterangan' => 'Hapus Role User',                                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'nama' => 'lihat.role-access',             'keterangan' => 'Lihat Role Access',                                    'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'nama' => 'tambah.role-access',            'keterangan' => 'Tambah Role Access',                                   'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'nama' => 'edit.role-access',              'keterangan' => 'Edit Role Access',                                     'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'nama' => 'hapus.role-access',             'keterangan' => 'Hapus Role Access',                                    'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'nama' => 'lihat.fakultas',                'keterangan' => 'Lihat Fakultas',                                       'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'nama' => 'tambah.fakultas',               'keterangan' => 'Tambah Fakultas',                                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'nama' => 'edit.fakultas',                 'keterangan' => 'Edit Fakultas',                                        'created_at' => now(), 'updated_at' => now()],
            ['id' => 20, 'nama' => 'hapus.fakultas',                'keterangan' => 'Hapus Fakultas',                                       'created_at' => now(), 'updated_at' => now()],
            ['id' => 21, 'nama' => 'lihat.program-studi',           'keterangan' => 'Lihat Program Studi',                                  'created_at' => now(), 'updated_at' => now()],
            ['id' => 22, 'nama' => 'tambah.program-studi',          'keterangan' => 'Tambah Program Studi',                                 'created_at' => now(), 'updated_at' => now()],
            ['id' => 23, 'nama' => 'edit.program-studi',            'keterangan' => 'Edit Program Studi',                                   'created_at' => now(), 'updated_at' => now()],
            ['id' => 24, 'nama' => 'hapus.program-studi',           'keterangan' => 'Hapus Program Studi',                                  'created_at' => now(), 'updated_at' => now()],
            ['id' => 25, 'nama' => 'lihat.jenis-kka',               'keterangan' => 'Lihat Jenis KKA',                                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 26, 'nama' => 'tambah.jenis-kka',              'keterangan' => 'Tambah Jenis KKA',                                     'created_at' => now(), 'updated_at' => now()],
            ['id' => 27, 'nama' => 'edit.jenis-kka',                'keterangan' => 'Edit Jenis KKA',                                       'created_at' => now(), 'updated_at' => now()],
            ['id' => 28, 'nama' => 'hapus.jenis-kka',               'keterangan' => 'Hapus Jenis KKA',                                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 29, 'nama' => 'lihat.tahun',                   'keterangan' => 'Lihat Tahun',                                          'created_at' => now(), 'updated_at' => now()],
            ['id' => 30, 'nama' => 'tambah.tahun',                  'keterangan' => 'Tambah Tahun',                                         'created_at' => now(), 'updated_at' => now()],
            ['id' => 31, 'nama' => 'edit.tahun',                    'keterangan' => 'Edit Tahun',                                           'created_at' => now(), 'updated_at' => now()],
            ['id' => 32, 'nama' => 'hapus.tahun',                   'keterangan' => 'Hapus Tahun',                                          'created_at' => now(), 'updated_at' => now()],
            ['id' => 33, 'nama' => 'lihat.periode',                 'keterangan' => 'Lihat Periode',                                        'created_at' => now(), 'updated_at' => now()],
            ['id' => 34, 'nama' => 'tambah.periode',                'keterangan' => 'Tambah Periode',                                       'created_at' => now(), 'updated_at' => now()],
            ['id' => 35, 'nama' => 'edit.periode',                  'keterangan' => 'Edit Periode',                                         'created_at' => now(), 'updated_at' => now()],
            ['id' => 36, 'nama' => 'hapus.periode',                 'keterangan' => 'Hapus Periode',                                        'created_at' => now(), 'updated_at' => now()],
            ['id' => 37, 'nama' => 'lihat.kegiatan',                'keterangan' => 'Lihat Kegiatan',                                       'created_at' => now(), 'updated_at' => now()],
            ['id' => 38, 'nama' => 'tambah.kegiatan',               'keterangan' => 'Tambah Kegiatan',                                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 39, 'nama' => 'edit.kegiatan',                 'keterangan' => 'Edit Kegiatan',                                        'created_at' => now(), 'updated_at' => now()],
            ['id' => 40, 'nama' => 'hapus.kegiatan',                'keterangan' => 'Hapus Kegiatan',                                       'created_at' => now(), 'updated_at' => now()],
            ['id' => 41, 'nama' => 'lihat.registrasi',              'keterangan' => 'Lihat Registrasi',                                     'created_at' => now(), 'updated_at' => now()],
            ['id' => 42, 'nama' => 'validasi.register',             'keterangan' => 'Validasi Registrasi',                                  'created_at' => now(), 'updated_at' => now()],
            ['id' => 43, 'nama' => 'hapus.registrasi',              'keterangan' => 'Hapus Registrasi',                                     'created_at' => now(), 'updated_at' => now()],
            ['id' => 44, 'nama' => 'lihat.dokumen-pembayaran',      'keterangan' => 'Lihat Dokumen Pembayaran',                             'created_at' => now(), 'updated_at' => now()],
            ['id' => 45, 'nama' => 'verifikasi.dokumen-pembayaran', 'keterangan' => 'Verifikasi Dokumen Pembayaran',                        'created_at' => now(), 'updated_at' => now()],
            ['id' => 46, 'nama' => 'verifikasi.sertifikat',         'keterangan' => 'Verifikasi Sertifikat Baca Quran',                     'created_at' => now(), 'updated_at' => now()],
            ['id' => 47, 'nama' => 'lihat.sertifikat',              'keterangan' => 'Lihat Sertifikat Baca Quran',                          'created_at' => now(), 'updated_at' => now()],
            ['id' => 48, 'nama' => 'lihat.dokumen-lainnya',         'keterangan' => 'Lihat Dokumen Lain',                                   'created_at' => now(), 'updated_at' => now()],
            ['id' => 49, 'nama' => 'verifikasi.dokumen-lainnya',    'keterangan' => 'Verifikasi Dokumen Lain',                              'created_at' => now(), 'updated_at' => now()],
            ['id' => 50, 'nama' => 'lihat.terverifikasi',           'keterangan' => 'Lihat Terverifikasi',                                  'created_at' => now(), 'updated_at' => now()],
            ['id' => 51, 'nama' => 'edit.terverifikasi',            'keterangan' => 'Edit Terverifikasi',                                   'created_at' => now(), 'updated_at' => now()],
            ['id' => 52, 'nama' => 'lihat.provinsi',                'keterangan' => 'Lihat Provinsi',                                       'created_at' => now(), 'updated_at' => now()],
            ['id' => 53, 'nama' => 'tambah.provinsi',               'keterangan' => 'Tambah Provinsi',                                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 54, 'nama' => 'edit.provinsi',                 'keterangan' => 'Edit Provinsi',                                        'created_at' => now(), 'updated_at' => now()],
            ['id' => 55, 'nama' => 'hapus.provinsi',                'keterangan' => 'Hapus Provinsi',                                       'created_at' => now(), 'updated_at' => now()],
            ['id' => 56, 'nama' => 'lihat.kabupaten',               'keterangan' => 'Lihat Kabupaten',                                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 57, 'nama' => 'tambah.kabupaten',              'keterangan' => 'Tambah Kabupaten',                                     'created_at' => now(), 'updated_at' => now()],
            ['id' => 58, 'nama' => 'edit.kabupaten',                'keterangan' => 'Edit Kabupaten',                                       'created_at' => now(), 'updated_at' => now()],
            ['id' => 59, 'nama' => 'hapus.kabupaten',               'keterangan' => 'Hapus Kabupaten',                                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 60, 'nama' => 'lihat.kecamatan',               'keterangan' => 'Lihat Kecamatan',                                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 61, 'nama' => 'tambah.kecamatan',              'keterangan' => 'Tambah Kecamatan',                                     'created_at' => now(), 'updated_at' => now()],
            ['id' => 62, 'nama' => 'edit.kecamatan',                'keterangan' => 'Edit Kecamatan',                                       'created_at' => now(), 'updated_at' => now()],
            ['id' => 63, 'nama' => 'hapus.kecamatan',               'keterangan' => 'Hapus Kecamatan',                                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 64, 'nama' => 'lihat.desa',                    'keterangan' => 'Lihat Desa',                                           'created_at' => now(), 'updated_at' => now()],
            ['id' => 65, 'nama' => 'tambah.desa',                   'keterangan' => 'Tambah Desa',                                          'created_at' => now(), 'updated_at' => now()],
            ['id' => 66, 'nama' => 'edit.desa',                     'keterangan' => 'Edit Desa',                                            'created_at' => now(), 'updated_at' => now()],
            ['id' => 67, 'nama' => 'hapus.desa',                    'keterangan' => 'Hapus Desa',                                           'created_at' => now(), 'updated_at' => now()],
            ['id' => 68, 'nama' => 'lihat.survey',                  'keterangan' => 'Lihat Survey',                                         'created_at' => now(), 'updated_at' => now()],
            ['id' => 69, 'nama' => 'tambah.survey',                 'keterangan' => 'Tambah Survey',                                        'created_at' => now(), 'updated_at' => now()],
            ['id' => 70, 'nama' => 'edit.survey',                   'keterangan' => 'Edit Survey',                                          'created_at' => now(), 'updated_at' => now()],
            ['id' => 71, 'nama' => 'hapus.survey',                  'keterangan' => 'Hapus Survey',                                         'created_at' => now(), 'updated_at' => now()],
            ['id' => 72, 'nama' => 'isi.survey',                    'keterangan' => 'Isi Survey',                                           'created_at' => now(), 'updated_at' => now()],
            ['id' => 73, 'nama' => 'verifikasi.survey',             'keterangan' => 'Verifikasi Survey',                                    'created_at' => now(), 'updated_at' => now()],
            ['id' => 74, 'nama' => 'atur.kelompok',                 'keterangan' => 'Mengatur Kelompok',                                    'created_at' => now(), 'updated_at' => now()],
            ['id' => 75, 'nama' => 'lihat.data-lokasi',             'keterangan' => 'Melihat Lokasi',                                       'created_at' => now(), 'updated_at' => now()],
            ['id' => 76, 'nama' => 'lihat.pegawai',                 'keterangan' => 'Lihat Pegawai',                                        'created_at' => now(), 'updated_at' => now()],
            ['id' => 77, 'nama' => 'tambah.pegawai',                'keterangan' => 'Tambah Pegawai',                                       'created_at' => now(), 'updated_at' => now()],
            ['id' => 78, 'nama' => 'edit.pegawai',                  'keterangan' => 'Edit Pegawai',                                         'created_at' => now(), 'updated_at' => now()],
            ['id' => 79, 'nama' => 'hapus.pegawai',                 'keterangan' => 'Hapus Pegawai',                                        'created_at' => now(), 'updated_at' => now()],
            ['id' => 80, 'nama' => 'lihat.dosen-pembimbing',        'keterangan' => 'Melihat halaman Dosen Pembimbing (kegiatan yang dibimbing)', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 81, 'nama' => 'lihat.berita',                  'keterangan' => 'Melihat daftar berita',                                'created_at' => now(), 'updated_at' => now()],
            ['id' => 82, 'nama' => 'tambah.berita',                 'keterangan' => 'Menambah berita baru',                                 'created_at' => now(), 'updated_at' => now()],
            ['id' => 83, 'nama' => 'edit.berita',                   'keterangan' => 'Mengedit berita',                                      'created_at' => now(), 'updated_at' => now()],
            ['id' => 84, 'nama' => 'hapus.berita',                  'keterangan' => 'Menghapus berita',                                     'created_at' => now(), 'updated_at' => now()],
            ['id' => 85, 'nama' => 'lihat.pengumuman',              'keterangan' => 'Melihat daftar pengumuman',                            'created_at' => now(), 'updated_at' => now()],
            ['id' => 86, 'nama' => 'tambah.pengumuman',             'keterangan' => 'Menambah pengumuman baru',                             'created_at' => now(), 'updated_at' => now()],
            ['id' => 87, 'nama' => 'edit.pengumuman',               'keterangan' => 'Mengedit pengumuman',                                  'created_at' => now(), 'updated_at' => now()],
            ['id' => 88, 'nama' => 'hapus.pengumuman',              'keterangan' => 'Menghapus pengumuman',                                 'created_at' => now(), 'updated_at' => now()],
            ['id' => 89, 'nama' => 'lihat.menu',                    'keterangan' => 'Lihat daftar menu navigasi',                           'created_at' => now(), 'updated_at' => now()],
            ['id' => 90, 'nama' => 'tambah.menu',                   'keterangan' => 'Tambah menu navigasi baru',                            'created_at' => now(), 'updated_at' => now()],
            ['id' => 91, 'nama' => 'edit.menu',                     'keterangan' => 'Edit menu navigasi',                                   'created_at' => now(), 'updated_at' => now()],
            ['id' => 92, 'nama' => 'hapus.menu',                    'keterangan' => 'Hapus menu navigasi',                                  'created_at' => now(), 'updated_at' => now()],
            ['id' => 93, 'nama' => 'lihat.halaman',                 'keterangan' => 'Lihat daftar halaman konten',                          'created_at' => now(), 'updated_at' => now()],
            ['id' => 94, 'nama' => 'tambah.halaman',                'keterangan' => 'Tambah halaman konten baru',                           'created_at' => now(), 'updated_at' => now()],
            ['id' => 95, 'nama' => 'edit.halaman',                  'keterangan' => 'Edit halaman konten',                                  'created_at' => now(), 'updated_at' => now()],
            ['id' => 96, 'nama' => 'hapus.halaman',                 'keterangan' => 'Hapus halaman konten',                                 'created_at' => now(), 'updated_at' => now()],
            ['id' => 97, 'nama' => 'lihat.manajemen-acces',         'keterangan' => 'Manajemen Access',                                     'created_at' => now(), 'updated_at' => now()],
            ['id' => 98, 'nama' => 'tambah.manajemen-acces',        'keterangan' => 'Tambah Manajemen Access',                              'created_at' => now(), 'updated_at' => now()],
            ['id' => 99, 'nama' => 'edit.manajemen-acces',          'keterangan' => 'Edit Manajemen Access',                                'created_at' => now(), 'updated_at' => now()],
            ['id' => 100,'nama' => 'hapus.manajemen-acces',         'keterangan' => 'Hapus Manajemen Access',                               'created_at' => now(), 'updated_at' => now()],
        ]);

        // ─── ROLE_ACCESS ──────────────────────────────────────────
        $roleAccess = [];

        // Administrator (1): semua kecuali 42, 43
        foreach (array_merge(range(1, 41), range(44, 100)) as $id) {
            $roleAccess[] = ['role_id' => 1, 'access_id' => $id];
        }

        // Panitia (3)
        foreach ([17, 21, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96] as $id) {
            $roleAccess[] = ['role_id' => 3, 'access_id' => $id];
        }

        // Program Studi (4)
        foreach ([41, 42, 43, 68, 75] as $id) {
            $roleAccess[] = ['role_id' => 4, 'access_id' => $id];
        }

        // TIM Survey (5)
        foreach ([68, 72] as $id) {
            $roleAccess[] = ['role_id' => 5, 'access_id' => $id];
        }

        // DPL (6), btq (7), Keuangan (8)
        $roleAccess[] = ['role_id' => 6, 'access_id' => 80];
        foreach ([46, 47, 48, 49] as $id) { $roleAccess[] = ['role_id' => 7, 'access_id' => $id]; }
        foreach ([44, 45] as $id)          { $roleAccess[] = ['role_id' => 8, 'access_id' => $id]; }

        DB::table('role_access')->insert($roleAccess);

        // ─── ADMIN USER ───────────────────────────────────────────
        $userId = DB::table('users')->insertGetId([
            'name'       => 'Administrator',
            'email'      => 'admin@umkendari.ac.id',
            'password'   => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_role')->insert([
            'user_id' => $userId,
            'role_id' => 1,
        ]);
    }
}
