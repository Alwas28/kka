<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanTahapan extends Model
{
    protected $table = 'kegiatan_tahapan';

    protected $fillable = ['kegiatan_id', 'nama', 'urutan', 'mulai', 'selesai'];

    protected $casts = [
        'mulai'   => 'date',
        'selesai' => 'date',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
