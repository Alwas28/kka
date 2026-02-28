<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanGrade extends Model
{
    protected $table = 'kegiatan_grade';

    protected $fillable = ['kegiatan_id', 'grade', 'nilai_min', 'nilai_max', 'urutan'];

    protected $casts = [
        'nilai_min' => 'float',
        'nilai_max' => 'float',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
