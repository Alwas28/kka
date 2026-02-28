<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanDokumen extends Model
{
    protected $table = 'kegiatan_dokumen';

    protected $fillable = [
        'kegiatan_id',
        'kategori',
        'nama',
        'is_wajib',
        'is_fixed',
        'urutan',
    ];

    protected $casts = [
        'is_wajib' => 'boolean',
        'is_fixed' => 'boolean',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
