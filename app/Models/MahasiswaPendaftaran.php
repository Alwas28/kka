<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaPendaftaran extends Model
{
    protected $table = 'mahasiswa_pendaftaran';

    protected $fillable = [
        'mahasiswa_id',
        'kegiatan_id',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_hp',
        'golongan_darah',
        'semester',
        'sks_ditempuh',
        'ipk',
        'ukuran_baju',
        'penyakit_diderita',
        'sedang_hamil',
        'catatan_kesehatan',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'sedang_hamil'  => 'boolean',
        'submitted_at'  => 'datetime',
        'ipk'           => 'decimal:2',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function dokumen()
    {
        return $this->hasMany(MahasiswaDokumen::class, 'mahasiswa_pendaftaran_id');
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }
}
