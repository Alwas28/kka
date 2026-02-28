<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiMahasiswa extends Model
{
    protected $table = 'nilai_mahasiswa';

    protected $fillable = [
        'mahasiswa_id',
        'survey_lokasi_id',
        'pegawai_id',
        'nilai_akhir',
        'catatan',
    ];

    protected $casts = [
        'nilai_akhir' => 'float',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function surveyLokasi()
    {
        return $this->belongsTo(SurveyLokasi::class);
    }

    public function dpl()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }


}
