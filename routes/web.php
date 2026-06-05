<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\MahasiswaDashboardController;
use App\Http\Controllers\MahasiswaPendaftaranController;
use App\Http\Controllers\MahasiswaRegisterController;
use App\Http\Controllers\JenisKkaController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\RegistrasiMahasiswaController;
use App\Http\Controllers\RoleAccessController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TahunController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\DokumenVerifikasiController;
use App\Http\Controllers\KabupatenController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\ProvinsiController;
use App\Http\Controllers\KelompokController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PelaksanaanController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\SurveyLokasiController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\DosenPembimbingController;
use App\Http\Controllers\HalamanController;
use App\Http\Controllers\MahasiswaAdminController;
use App\Http\Controllers\MahasiswaProfilController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\PengumumanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $beritaTerbaru = \App\Models\Berita::where('status', 'published')
        ->latest('published_at')
        ->take(3)
        ->get();
    $pengumumanAktif = \App\Models\Pengumuman::where('status', 'aktif')
        ->where(function ($q) {
            $q->whereNull('tanggal_selesai')
              ->orWhere('tanggal_selesai', '>=', today());
        })
        ->where('tanggal_mulai', '<=', today())
        ->orderByDesc('is_penting')
        ->orderByDesc('created_at')
        ->take(10)
        ->get();
    $navMenus = \App\Models\Menu::activeNav();
    return view('welcome', compact('beritaTerbaru', 'pengumumanAktif', 'navMenus'));
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Public pages (no auth required)
Route::get('/info/berita/{berita:slug}', [BeritaController::class, 'show'])->name('berita.show');
Route::get('/info/pengumuman/{pengumuman}', [PengumumanController::class, 'show'])->name('pengumuman.show');
Route::get('/halaman/{page:slug}', [HalamanController::class, 'show'])->name('halaman.show');

// Registrasi Mahasiswa (publik)
Route::get('/daftar', [MahasiswaRegisterController::class, 'showForm'])->name('mahasiswa.register');
Route::post('/daftar', [MahasiswaRegisterController::class, 'register'])->name('mahasiswa.register.post');

// Portal Mahasiswa (dilindungi mahasiswa.auth)
Route::middleware('mahasiswa.auth')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [MahasiswaDashboardController::class, 'logout'])->name('logout');

    // Notifikasi
    Route::post('/notifikasi/{notifikasi}/read', [MahasiswaDashboardController::class, 'readNotifikasi'])->name('notifikasi.read');
    Route::post('/notifikasi/read-all', [MahasiswaDashboardController::class, 'readAllNotifikasi'])->name('notifikasi.readAll');

    // Form Pendaftaran
    Route::get('/pendaftaran', [MahasiswaPendaftaranController::class, 'showForm'])->name('pendaftaran.form');
    Route::post('/pendaftaran', [MahasiswaPendaftaranController::class, 'save'])->name('pendaftaran.save');
    Route::post('/pendaftaran/submit', [MahasiswaPendaftaranController::class, 'submit'])->name('pendaftaran.submit');
    Route::post('/pendaftaran/dokumen/{kegiatanDokumen}', [MahasiswaPendaftaranController::class, 'uploadDokumen'])->name('pendaftaran.dokumen.upload');
    Route::delete('/pendaftaran/dokumen/{dokumen}', [MahasiswaPendaftaranController::class, 'hapusDokumen'])->name('pendaftaran.dokumen.hapus');

    // Pelaksanaan
    Route::get('/pelaksanaan', [PelaksanaanController::class, 'index'])->name('pelaksanaan.index');
    Route::post('/pelaksanaan/logbook', [PelaksanaanController::class, 'storeLogbook'])->name('pelaksanaan.logbook.store');
    Route::put('/pelaksanaan/logbook/{logbook}', [PelaksanaanController::class, 'updateLogbook'])->name('pelaksanaan.logbook.update');
    Route::delete('/pelaksanaan/logbook/{logbook}', [PelaksanaanController::class, 'destroyLogbook'])->name('pelaksanaan.logbook.destroy');
    Route::post('/pelaksanaan/laporan-individu/{dokumen}', [PelaksanaanController::class, 'uploadLaporanIndividu'])->name('pelaksanaan.laporan-individu.upload');
    Route::delete('/pelaksanaan/laporan-individu/{dokumen}', [PelaksanaanController::class, 'hapusLaporanIndividu'])->name('pelaksanaan.laporan-individu.hapus');
    Route::post('/pelaksanaan/laporan-akhir/{dokumen}', [PelaksanaanController::class, 'uploadLaporanAkhir'])->name('pelaksanaan.laporan-akhir.upload');
    Route::delete('/pelaksanaan/laporan-akhir/{dokumen}', [PelaksanaanController::class, 'hapusLaporanAkhir'])->name('pelaksanaan.laporan-akhir.hapus');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Manajemen Access
    Route::get('/access', [AccessController::class, 'index'])->name('access.index');
    Route::post('/access', [AccessController::class, 'store'])->name('access.store');
    Route::put('/access/{access}', [AccessController::class, 'update'])->name('access.update');
    Route::delete('/access/{access}', [AccessController::class, 'destroy'])->name('access.destroy');

    // Manajemen Role
    Route::get('/role', [RoleController::class, 'index'])->name('role.index');
    Route::post('/role', [RoleController::class, 'store'])->name('role.store');
    Route::put('/role/{role}', [RoleController::class, 'update'])->name('role.update');
    Route::delete('/role/{role}', [RoleController::class, 'destroy'])->name('role.destroy');

    // Kelola Hak Akses per Role
    Route::get('/role/{role}/access', [RoleAccessController::class, 'show'])->name('role.access.show');
    Route::post('/role/{role}/access', [RoleAccessController::class, 'update'])->name('role.access.update');

    // Manajemen User Role
    Route::get('/user-role', [UserRoleController::class, 'index'])->name('user-role.index');
    Route::post('/user-role/{user}', [UserRoleController::class, 'update'])->name('user-role.update');

    // Master Data - Fakultas
    Route::get('/fakultas', [FakultasController::class, 'index'])->name('fakultas.index');
    Route::post('/fakultas', [FakultasController::class, 'store'])->name('fakultas.store');
    Route::put('/fakultas/{fakultas}', [FakultasController::class, 'update'])->name('fakultas.update');
    Route::delete('/fakultas/{fakultas}', [FakultasController::class, 'destroy'])->name('fakultas.destroy');

    // Master Data - Program Studi
    Route::get('/program-studi', [ProgramStudiController::class, 'index'])->name('program-studi.index');
    Route::post('/program-studi', [ProgramStudiController::class, 'store'])->name('program-studi.store');
    Route::put('/program-studi/{programStudi}', [ProgramStudiController::class, 'update'])->name('program-studi.update');
    Route::delete('/program-studi/{programStudi}', [ProgramStudiController::class, 'destroy'])->name('program-studi.destroy');

    // Master Data - Pegawai
    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::post('/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::put('/pegawai/{pegawai}', [PegawaiController::class, 'update'])->name('pegawai.update');
    Route::delete('/pegawai/{pegawai}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
    Route::post('/pegawai/{pegawai}/buat-akun', [PegawaiController::class, 'buatAkun'])->name('pegawai.buat-akun');

    // Master Data - Provinsi
    Route::get('/provinsi', [ProvinsiController::class, 'index'])->name('provinsi.index');
    Route::post('/provinsi', [ProvinsiController::class, 'store'])->name('provinsi.store');
    Route::put('/provinsi/{provinsi}', [ProvinsiController::class, 'update'])->name('provinsi.update');
    Route::delete('/provinsi/{provinsi}', [ProvinsiController::class, 'destroy'])->name('provinsi.destroy');

    // Master Data - Kabupaten
    Route::get('/kabupaten', [KabupatenController::class, 'index'])->name('kabupaten.index');
    Route::post('/kabupaten', [KabupatenController::class, 'store'])->name('kabupaten.store');
    Route::put('/kabupaten/{kabupaten}', [KabupatenController::class, 'update'])->name('kabupaten.update');
    Route::delete('/kabupaten/{kabupaten}', [KabupatenController::class, 'destroy'])->name('kabupaten.destroy');
    Route::get('/api/kabupaten', [KabupatenController::class, 'json'])->name('api.kabupaten');

    // Master Data - Kecamatan
    Route::get('/kecamatan', [KecamatanController::class, 'index'])->name('kecamatan.index');
    Route::post('/kecamatan', [KecamatanController::class, 'store'])->name('kecamatan.store');
    Route::put('/kecamatan/{kecamatan}', [KecamatanController::class, 'update'])->name('kecamatan.update');
    Route::delete('/kecamatan/{kecamatan}', [KecamatanController::class, 'destroy'])->name('kecamatan.destroy');
    Route::get('/api/kecamatan', [KecamatanController::class, 'json'])->name('api.kecamatan');

    // Master Data - Desa/Kelurahan
    Route::get('/desa', [DesaController::class, 'index'])->name('desa.index');
    Route::post('/desa', [DesaController::class, 'store'])->name('desa.store');
    Route::put('/desa/{desa}', [DesaController::class, 'update'])->name('desa.update');
    Route::delete('/desa/{desa}', [DesaController::class, 'destroy'])->name('desa.destroy');
    Route::get('/api/desa', [DesaController::class, 'json'])->name('api.desa');

    // Manajemen User
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Master Data - Jenis KKA
    Route::get('/jenis-kka', [JenisKkaController::class, 'index'])->name('jenis-kka.index');
    Route::post('/jenis-kka', [JenisKkaController::class, 'store'])->name('jenis-kka.store');
    Route::put('/jenis-kka/{jenisKka}', [JenisKkaController::class, 'update'])->name('jenis-kka.update');
    Route::delete('/jenis-kka/{jenisKka}', [JenisKkaController::class, 'destroy'])->name('jenis-kka.destroy');

    // Master Data - Tahun
    Route::get('/tahun', [TahunController::class, 'index'])->name('tahun.index');
    Route::post('/tahun', [TahunController::class, 'store'])->name('tahun.store');
    Route::put('/tahun/{tahun}', [TahunController::class, 'update'])->name('tahun.update');
    Route::delete('/tahun/{tahun}', [TahunController::class, 'destroy'])->name('tahun.destroy');

    // Master Data - Periode
    Route::get('/periode', [PeriodeController::class, 'index'])->name('periode.index');
    Route::post('/periode', [PeriodeController::class, 'store'])->name('periode.store');
    Route::put('/periode/{periode}', [PeriodeController::class, 'update'])->name('periode.update');
    Route::delete('/periode/{periode}', [PeriodeController::class, 'destroy'])->name('periode.destroy');

    // Registrasi Mahasiswa (validasi prodi)
    Route::get('/registrasi', [RegistrasiMahasiswaController::class, 'index'])->name('registrasi.index');
    Route::get('/registrasi/disetujui', [RegistrasiMahasiswaController::class, 'disetujui'])->name('registrasi.disetujui');
    Route::post('/registrasi/{mahasiswa}/setujui', [RegistrasiMahasiswaController::class, 'setujui'])->name('registrasi.setujui');
    Route::post('/registrasi/{mahasiswa}/tolak', [RegistrasiMahasiswaController::class, 'tolak'])->name('registrasi.tolak');
    Route::post('/registrasi/{mahasiswa}/kembalikan', [RegistrasiMahasiswaController::class, 'kembalikan'])->name('registrasi.kembalikan');

    // Verifikasi Dokumen
    Route::get('/dokumen/pembayaran', [DokumenVerifikasiController::class, 'pembayaran'])->name('dokumen.pembayaran');
    Route::post('/dokumen/{dokumen}/pembayaran-terima', [DokumenVerifikasiController::class, 'terima'])->name('dokumen.pembayaran.terima');
    Route::post('/dokumen/{dokumen}/pembayaran-tolak', [DokumenVerifikasiController::class, 'tolak'])->name('dokumen.pembayaran.tolak');

    Route::get('/dokumen/sertifikat', [DokumenVerifikasiController::class, 'sertifikat'])->name('dokumen.sertifikat');
    Route::post('/dokumen/{dokumen}/sertifikat-terima', [DokumenVerifikasiController::class, 'terima'])->name('dokumen.sertifikat.terima');
    Route::post('/dokumen/{dokumen}/sertifikat-tolak', [DokumenVerifikasiController::class, 'tolak'])->name('dokumen.sertifikat.tolak');

    Route::get('/dokumen/lainnya', [DokumenVerifikasiController::class, 'dokumenLainnya'])->name('dokumen.lainnya');
    Route::post('/dokumen/{dokumen}/lainnya-terima', [DokumenVerifikasiController::class, 'terima'])->name('dokumen.lainnya.terima');
    Route::post('/dokumen/{dokumen}/lainnya-tolak', [DokumenVerifikasiController::class, 'tolak'])->name('dokumen.lainnya.tolak');

    Route::get('/dokumen/terverifikasi', [DokumenVerifikasiController::class, 'terverifikasi'])->name('dokumen.terverifikasi');
    Route::post('/dokumen/terverifikasi/{mahasiswa}/revert', [DokumenVerifikasiController::class, 'revertVerifikasi'])->name('dokumen.terverifikasi.revert');

    // Survey Lokasi
    Route::get('/survey', [SurveyLokasiController::class, 'index'])->name('survey.index');
    Route::post('/survey', [SurveyLokasiController::class, 'store'])->name('survey.store');
    Route::get('/survey/hasil', [SurveyLokasiController::class, 'hasilSurvey'])->name('survey.hasil');
    Route::get('/survey/data-lokasi', [SurveyLokasiController::class, 'dataLokasi'])->name('survey.data-lokasi');
    Route::get('/survey/{survey}/isi', [SurveyLokasiController::class, 'isiSurvey'])->name('survey.isi');
    Route::post('/survey/{survey}/isi', [SurveyLokasiController::class, 'simpanSurvey'])->name('survey.simpan');
    Route::post('/survey/{survey}/setujui', [SurveyLokasiController::class, 'setujui'])->name('survey.setujui');
    Route::post('/survey/{survey}/kelompok', [SurveyLokasiController::class, 'setKelompok'])->name('survey.kelompok');
    Route::put('/survey/{survey}', [SurveyLokasiController::class, 'update'])->name('survey.update');
    Route::delete('/survey/{survey}', [SurveyLokasiController::class, 'destroy'])->name('survey.destroy');

    // Peserta & DPL
    Route::get('/peserta', [PesertaController::class, 'index'])->name('peserta.index');
    Route::get('/dpl', [PesertaController::class, 'dpl'])->name('dpl.index');

    // Rekap
    Route::get('/rekap/pendaftaran', [RekapController::class, 'pendaftaran'])->name('rekap.pendaftaran');
    Route::get('/rekap/pendaftaran/{kegiatanId}', [RekapController::class, 'detail'])->name('rekap.pendaftaran.detail');
    Route::get('/rekap/pendaftaran/{kegiatanId}/export', [RekapController::class, 'export'])->name('rekap.pendaftaran.export');
    Route::get('/rekap/pendaftaran/{kegiatanId}/grafik', [RekapController::class, 'grafik'])->name('rekap.pendaftaran.grafik');

    // Data Mahasiswa (Admin)
    Route::get('/mahasiswa', [MahasiswaAdminController::class, 'index'])->name('mahasiswa.admin.index');
    Route::put('/mahasiswa/{mahasiswa}/update-data', [MahasiswaAdminController::class, 'update'])->name('mahasiswa.admin.update');
    Route::delete('/mahasiswa/{mahasiswa}/hapus', [MahasiswaAdminController::class, 'destroy'])->name('mahasiswa.admin.destroy');

    // Profil Mahasiswa (generik — dapat diakses dari menu manapun)
    Route::get('/mahasiswa/{mahasiswa}/profil', [MahasiswaProfilController::class, 'show'])->name('mahasiswa.profil');

    // Dosen Pembimbing (DPL section)
    Route::get('/dosen-pembimbing',              [DosenPembimbingController::class, 'index'])->name('dosen-pembimbing.index');
    Route::get('/dosen-pembimbing/{kegiatan}',   [DosenPembimbingController::class, 'detail'])->name('dosen-pembimbing.detail');
    Route::post('/dosen-pembimbing/{kegiatan}/nilai', [DosenPembimbingController::class, 'saveNilai'])->name('dosen-pembimbing.nilai');

    // Setup Kelompok
    Route::get('/kelompok/{survey}/setup', [KelompokController::class, 'setup'])->name('kelompok.setup');
    Route::post('/kelompok/{survey}/mahasiswa', [KelompokController::class, 'tambahMahasiswa'])->name('kelompok.tambah-mahasiswa');
    Route::delete('/kelompok/{survey}/mahasiswa/{mahasiswa}', [KelompokController::class, 'hapusMahasiswa'])->name('kelompok.hapus-mahasiswa');
    Route::post('/kelompok/{survey}/mahasiswa/{mahasiswa}/koordinator', [KelompokController::class, 'setKoordinator'])->name('kelompok.koordinator');
    Route::post('/kelompok/{survey}/dosen', [KelompokController::class, 'tambahDosen'])->name('kelompok.tambah-dosen');
    Route::delete('/kelompok/{survey}/dosen/{pegawai}', [KelompokController::class, 'hapusDosen'])->name('kelompok.hapus-dosen');

    // Upload gambar dalam konten editor
    Route::post('/upload/gambar-konten', [BeritaController::class, 'uploadGambarKonten'])->name('upload.gambar-konten');

    // Berita
    Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/create', [BeritaController::class, 'create'])->name('berita.create');
    Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');
    Route::get('/berita/{berita}/edit', [BeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/berita/{berita}', [BeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{berita}', [BeritaController::class, 'destroy'])->name('berita.destroy');

    // Pengumuman
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('/pengumuman/create', [PengumumanController::class, 'create'])->name('pengumuman.create');
    Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::get('/pengumuman/{pengumuman}/edit', [PengumumanController::class, 'edit'])->name('pengumuman.edit');
    Route::put('/pengumuman/{pengumuman}', [PengumumanController::class, 'update'])->name('pengumuman.update');
    Route::delete('/pengumuman/{pengumuman}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');
    Route::post('/pengumuman/{pengumuman}/toggle', [PengumumanController::class, 'toggleStatus'])->name('pengumuman.toggle');

    // Menu Navigasi
    Route::get('/kelola/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::get('/kelola/menu/create', [MenuController::class, 'create'])->name('menu.create');
    Route::post('/kelola/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::get('/kelola/menu/{menu}/edit', [MenuController::class, 'edit'])->name('menu.edit');
    Route::put('/kelola/menu/{menu}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/kelola/menu/{menu}', [MenuController::class, 'destroy'])->name('menu.destroy');

    // Halaman Konten
    Route::get('/kelola/halaman', [HalamanController::class, 'index'])->name('halaman.index');
    Route::get('/kelola/halaman/create', [HalamanController::class, 'create'])->name('halaman.create');
    Route::post('/kelola/halaman', [HalamanController::class, 'store'])->name('halaman.store');
    Route::get('/kelola/halaman/{page}/edit', [HalamanController::class, 'edit'])->name('halaman.edit');
    Route::put('/kelola/halaman/{page}', [HalamanController::class, 'update'])->name('halaman.update');
    Route::delete('/kelola/halaman/{page}', [HalamanController::class, 'destroy'])->name('halaman.destroy');

    // Kegiatan
    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
    Route::get('/kegiatan/create', [KegiatanController::class, 'create'])->name('kegiatan.create');
    Route::get('/kegiatan/berlangsung', [KegiatanController::class, 'berlangsung'])->name('kegiatan.berlangsung');
    Route::get('/kegiatan/selesai', [KegiatanController::class, 'selesai'])->name('kegiatan.selesai');
    Route::post('/kegiatan', [KegiatanController::class, 'store'])->name('kegiatan.store');
    Route::get('/kegiatan/{kegiatan}/edit', [KegiatanController::class, 'edit'])->name('kegiatan.edit');
    Route::put('/kegiatan/{kegiatan}', [KegiatanController::class, 'update'])->name('kegiatan.update');
    Route::delete('/kegiatan/{kegiatan}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');
    Route::post('/kegiatan/{kegiatan}/toggle-aktif', [KegiatanController::class, 'toggleAktif'])->name('kegiatan.toggle-aktif');
});

require __DIR__.'/auth.php';
