<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanDokumen;
use App\Models\MahasiswaDokumen;
use App\Models\MahasiswaPendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MahasiswaPendaftaranController extends Controller
{
    /**
     * Tampilkan form pendaftaran.
     */
    public function showForm()
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();
        $mahasiswa->load('pendaftaran.kegiatan', 'level');

        if ($mahasiswa->mahasiswa_level_id < 2) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Anda belum disetujui oleh program studi. Silakan tunggu konfirmasi.');
        }

        $level       = $mahasiswa->mahasiswa_level_id;
        $pendaftaran = $mahasiswa->pendaftaran;

        // Form fields selalu readonly setelah pertama kali submit (level >= 3)
        $isFormReadOnly = $level >= 3;

        // Level 3 = menunggu verifikasi (semua readonly)
        // Level 4 = perbaiki dokumen yang ditolak (bisa re-upload)
        // Level 5 = semua dokumen diterima (selesai, semua readonly)
        $isFullReadOnly = $level >= 5;

        $kegiatanList = Kegiatan::with(['jenisKka', 'tahun', 'periode'])
            ->whereDate('kegiatan_selesai', '>=', today())
            ->orderBy('kegiatan_mulai')
            ->get();

        // Dokumen yang disyaratkan untuk kegiatan yang dipilih
        $dokumenList      = collect();
        $uploadedDokumen  = collect();
        $allWajibUploaded = false;
        $hasDitolak       = false;
        $allDiterima      = false;
        $canResubmit      = false;

        if ($pendaftaran?->kegiatan_id) {
            $dokumenList = KegiatanDokumen::where('kegiatan_id', $pendaftaran->kegiatan_id)
                ->where('kategori', 'pendaftaran')
                ->orderBy('urutan')
                ->get();

            $uploadedDokumen = MahasiswaDokumen::where('mahasiswa_pendaftaran_id', $pendaftaran->id)
                ->get()
                ->keyBy('kegiatan_dokumen_id');

            $wajibIds         = $dokumenList->where('is_wajib', true)->pluck('id');
            $allWajibUploaded = $wajibIds->every(fn($id) => $uploadedDokumen->has($id));

            // Cek status verifikasi dokumen
            $hasDitolak  = $uploadedDokumen->contains('status', 'ditolak');
            $allDiterima = $level >= 5;

            // Level 4: bisa kirim ulang jika tidak ada lagi dokumen "ditolak"
            // (artinya semua yang ditolak sudah di-upload ulang → status jadi "pending")
            $canResubmit = $level == 4 && !$hasDitolak;
        }

        return view('mahasiswa.pendaftaran.form', compact(
            'mahasiswa', 'pendaftaran', 'kegiatanList',
            'isFormReadOnly', 'isFullReadOnly',
            'dokumenList', 'uploadedDokumen', 'allWajibUploaded',
            'hasDitolak', 'allDiterima', 'canResubmit'
        ));
    }

    /**
     * Simpan form pendaftaran sebagai draft.
     */
    public function save(Request $request)
    {
        $mahasiswa = Auth::guard('mahasiswa')->user();

        if ($mahasiswa->mahasiswa_level_id < 2) {
            return back()->with('error', 'Anda belum disetujui prodi.');
        }
        if ($mahasiswa->mahasiswa_level_id >= 3) {
            return back()->with('error', 'Pendaftaran sudah dikirim dan tidak dapat diubah.');
        }

        $rules = [
            'kegiatan_id'        => 'required|exists:kegiatan,id',
            'tempat_lahir'       => 'required|string|max:100',
            'tanggal_lahir'      => 'required|date|before:today',
            'jenis_kelamin'      => 'required|in:L,P',
            'alamat'             => 'required|string|max:500',
            'no_hp'              => 'required|string|max:20',
            'golongan_darah'     => 'nullable|in:A,B,AB,O,Tidak Tahu',
            'semester'           => 'required|integer|min:1|max:14',
            'sks_ditempuh'       => 'required|integer|min:0|max:250',
            'ipk'                => 'required|numeric|min:0|max:4',
            'ukuran_baju'        => 'required|in:XS,S,M,L,XL,XXL,XXXL',
            'penyakit_diderita'  => 'nullable|string|max:500',
            'sedang_hamil'       => 'nullable|boolean',
            'catatan_kesehatan'  => 'nullable|string|max:500',
        ];

        $messages = [
            'kegiatan_id.required'    => 'Kegiatan KKA wajib dipilih.',
            'tempat_lahir.required'   => 'Tempat lahir wajib diisi.',
            'tanggal_lahir.required'  => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before'    => 'Tanggal lahir tidak valid.',
            'jenis_kelamin.required'  => 'Jenis kelamin wajib dipilih.',
            'alamat.required'         => 'Alamat wajib diisi.',
            'no_hp.required'          => 'Nomor HP wajib diisi.',
            'semester.required'       => 'Semester wajib diisi.',
            'semester.min'            => 'Semester minimal 1.',
            'sks_ditempuh.required'   => 'SKS yang ditempuh wajib diisi.',
            'ipk.required'            => 'IPK wajib diisi.',
            'ipk.max'                 => 'IPK maksimal 4.00.',
            'ukuran_baju.required'    => 'Ukuran baju wajib dipilih.',
        ];

        $request->validate($rules, $messages);

        $data = $request->only([
            'kegiatan_id', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
            'alamat', 'no_hp', 'golongan_darah',
            'semester', 'sks_ditempuh', 'ipk',
            'ukuran_baju',
            'penyakit_diderita', 'catatan_kesehatan',
        ]);

        $data['sedang_hamil'] = ($request->jenis_kelamin === 'P')
            ? $request->boolean('sedang_hamil')
            : null;

        $data['mahasiswa_id'] = $mahasiswa->id;
        $data['status']       = 'draft';
        $data['submitted_at'] = null;

        MahasiswaPendaftaran::updateOrCreate(
            ['mahasiswa_id' => $mahasiswa->id],
            $data
        );

        return back()->with('success', 'Draft pendaftaran berhasil disimpan. Silakan upload dokumen yang disyaratkan.');
    }

    /**
     * Upload satu dokumen persyaratan.
     * Level 2: upload dokumen baru
     * Level 4: re-upload dokumen yang ditolak
     */
    public function uploadDokumen(Request $request, KegiatanDokumen $kegiatanDokumen)
    {
        $mahasiswa   = Auth::guard('mahasiswa')->user();
        $pendaftaran = $mahasiswa->pendaftaran;
        $level       = $mahasiswa->mahasiswa_level_id;

        abort_if(!$pendaftaran, 404, 'Pendaftaran belum diisi.');
        abort_if($kegiatanDokumen->kegiatan_id !== $pendaftaran->kegiatan_id, 403, 'Dokumen tidak sesuai kegiatan.');

        // Cek izin upload
        $existing = MahasiswaDokumen::where('mahasiswa_pendaftaran_id', $pendaftaran->id)
            ->where('kegiatan_dokumen_id', $kegiatanDokumen->id)
            ->first();

        if ($level == 2) {
            // Level 2: bebas upload/replace
        } elseif ($level == 4) {
            // Level 4: upload baru (setelah hapus ditolak) atau dokumen yang belum ada
            // Tidak boleh replace dokumen yang sudah diterima
            abort_if($existing && $existing->status === 'diterima', 422, 'Dokumen yang diterima tidak dapat diganti.');
        } else {
            abort(422, 'Tidak dapat mengupload dokumen pada tahap ini.');
        }

        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'file.required' => 'File wajib dipilih.',
            'file.mimes'    => 'File harus berformat PDF, JPG, atau PNG.',
            'file.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        // Hapus file lama jika sudah ada
        if ($existing) {
            Storage::disk('public')->delete($existing->file_path);
            $existing->delete();
        }

        $file     = $request->file('file');
        $dir      = 'mahasiswa_dokumen/' . $pendaftaran->id;
        $fileName = $kegiatanDokumen->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs($dir, $fileName, 'public');

        MahasiswaDokumen::create([
            'mahasiswa_pendaftaran_id' => $pendaftaran->id,
            'kegiatan_dokumen_id'      => $kegiatanDokumen->id,
            'file_path'                => $path,
            'file_name'                => $file->getClientOriginalName(),
            'file_size'                => $file->getSize(),
            'status'                   => 'pending',
        ]);

        return back()->with('success', 'Dokumen "' . $kegiatanDokumen->nama . '" berhasil diupload.');
    }

    /**
     * Hapus satu dokumen yang sudah diupload.
     * Hanya boleh di level 2 (draft) — dokumen diterima tidak bisa dihapus.
     */
    public function hapusDokumen(MahasiswaDokumen $dokumen)
    {
        $mahasiswa   = Auth::guard('mahasiswa')->user();
        $pendaftaran = $mahasiswa->pendaftaran;

        abort_if(!$pendaftaran || $dokumen->mahasiswa_pendaftaran_id !== $pendaftaran->id, 403);
        $level = $mahasiswa->mahasiswa_level_id;

        // Level 2: hapus semua kecuali diterima
        // Level 4: hapus hanya yang ditolak (untuk upload ulang)
        if ($level == 2) {
            abort_if($dokumen->status === 'diterima', 422, 'Dokumen yang diterima tidak bisa dihapus.');
        } elseif ($level == 4) {
            abort_if($dokumen->status !== 'ditolak', 422, 'Hanya dokumen yang ditolak yang dapat dihapus.');
        } else {
            abort(422, 'Dokumen tidak dapat dihapus pada tahap ini.');
        }

        Storage::disk('public')->delete($dokumen->file_path);
        $dokumen->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    /**
     * Kirim pendaftaran.
     * Level 2: kirim pertama kali → level 3
     * Level 4: kirim ulang setelah perbaiki dokumen ditolak → level 3
     */
    public function submit()
    {
        $mahasiswa   = Auth::guard('mahasiswa')->user();
        $pendaftaran = $mahasiswa->pendaftaran;
        $level       = $mahasiswa->mahasiswa_level_id;

        if (!$pendaftaran) {
            return back()->with('error', 'Silakan isi dan simpan form pendaftaran terlebih dahulu sebelum mengirim.');
        }

        if ($level == 2) {
            // Kirim pertama kali
            if ($pendaftaran->status !== 'draft') {
                return back()->with('error', 'Pendaftaran Anda sudah pernah dikirim sebelumnya.');
            }

            $dokumenWajibIds = KegiatanDokumen::where('kegiatan_id', $pendaftaran->kegiatan_id)
                ->where('kategori', 'pendaftaran')
                ->where('is_wajib', true)
                ->pluck('id');

            $uploadedIds = MahasiswaDokumen::where('mahasiswa_pendaftaran_id', $pendaftaran->id)
                ->pluck('kegiatan_dokumen_id');

            if ($dokumenWajibIds->diff($uploadedIds)->isNotEmpty()) {
                return back()->with('error', 'Masih ada dokumen wajib yang belum diupload.');
            }

            $pendaftaran->update([
                'status'       => 'submitted',
                'submitted_at' => now(),
            ]);

            $mahasiswa->update(['mahasiswa_level_id' => 3]);

            return redirect()->route('mahasiswa.dashboard')
                ->with('success', 'Pendaftaran berhasil dikirim! Silakan menunggu verifikasi dokumen.');

        } elseif ($level == 4) {
            // Kirim ulang setelah perbaiki dokumen yang ditolak
            $hasDitolak = MahasiswaDokumen::where('mahasiswa_pendaftaran_id', $pendaftaran->id)
                ->where('status', 'ditolak')
                ->exists();

            if ($hasDitolak) {
                return back()->with('error', 'Masih ada dokumen yang ditolak dan belum di-upload ulang.');
            }

            $pendaftaran->update([
                'status'       => 'submitted',
                'submitted_at' => now(),
            ]);

            $mahasiswa->update(['mahasiswa_level_id' => 3]);

            return redirect()->route('mahasiswa.dashboard')
                ->with('success', 'Pendaftaran berhasil dikirim ulang! Silakan menunggu verifikasi dokumen.');

        } else {
            return back()->with('error', 'Tidak dapat mengirim pendaftaran pada tahap ini.');
        }
    }
}
