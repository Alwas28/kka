<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanIndividu extends Model
{
    protected $table = 'laporan_individu';

    protected $fillable = [
        'mahasiswa_id',
        'survey_lokasi_id',
        'kegiatan_dokumen_id',
        'file_path',
        'file_name',
        'file_size',
        'keterangan',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function surveyLokasi()
    {
        return $this->belongsTo(SurveyLokasi::class);
    }

    public function kegiatanDokumen()
    {
        return $this->belongsTo(KegiatanDokumen::class);
    }
}
