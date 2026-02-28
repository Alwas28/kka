<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaNotifikasi extends Model
{
    protected $table = 'mahasiswa_notifikasi';

    protected $fillable = [
        'mahasiswa_id',
        'judul',
        'pesan',
        'ikon',
        'warna',
        'url',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }
}
