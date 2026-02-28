<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaDokumen extends Model
{
    protected $table = 'mahasiswa_dokumen';

    protected $fillable = [
        'mahasiswa_pendaftaran_id',
        'kegiatan_dokumen_id',
        'file_path',
        'file_name',
        'file_size',
        'status',
        'catatan_verifikasi',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(MahasiswaPendaftaran::class, 'mahasiswa_pendaftaran_id');
    }

    public function kegiatanDokumen()
    {
        return $this->belongsTo(KegiatanDokumen::class, 'kegiatan_dokumen_id');
    }

    /** Format ukuran file (KB / MB). */
    public function getFileSizeFormattedAttribute(): string
    {
        if ($this->file_size < 1024 * 1024) {
            return round($this->file_size / 1024, 1) . ' KB';
        }
        return round($this->file_size / (1024 * 1024), 2) . ' MB';
    }
}
