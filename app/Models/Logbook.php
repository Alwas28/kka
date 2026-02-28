<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $table = 'logbook';

    protected $fillable = [
        'mahasiswa_id',
        'survey_lokasi_id',
        'tanggal',
        'kegiatan_dilakukan',
        'lokasi',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function surveyLokasi()
    {
        return $this->belongsTo(SurveyLokasi::class);
    }
}
