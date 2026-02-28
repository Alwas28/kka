<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanAkhir extends Model
{
    protected $table = 'laporan_akhir';

    protected $fillable = [
        'survey_lokasi_id',
        'mahasiswa_id',
        'kegiatan_dokumen_id',
        'file_path',
        'file_name',
        'file_size',
        'keterangan',
    ];

    public function surveyLokasi()
    {
        return $this->belongsTo(SurveyLokasi::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function kegiatanDokumen()
    {
        return $this->belongsTo(KegiatanDokumen::class);
    }
}
