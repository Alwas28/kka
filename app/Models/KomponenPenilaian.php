<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomponenPenilaian extends Model
{
    protected $table = 'kegiatan_komponen_penilaian';

    protected $fillable = ['kegiatan_id', 'nama', 'persentase', 'urutan'];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
