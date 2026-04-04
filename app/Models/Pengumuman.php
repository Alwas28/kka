<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';

    protected $fillable = [
        'user_id',
        'judul',
        'konten',
        'gambar',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_penting',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'is_penting'      => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }
}
