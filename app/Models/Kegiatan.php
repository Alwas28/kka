<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan';

    protected $fillable = [
        'jenis_kka_id',
        'tahun_id',
        'periode_id',
        'nama',
        'kegiatan_mulai',
        'kegiatan_selesai',
        'logbook_aktif',
        'laporan_aktif',
    ];

    protected $casts = [
        'kegiatan_mulai'   => 'date',
        'kegiatan_selesai' => 'date',
        'logbook_aktif'    => 'boolean',
        'laporan_aktif'    => 'boolean',
    ];

    protected $appends = ['status'];

    public function jenisKka()
    {
        return $this->belongsTo(JenisKka::class);
    }

    public function tahun()
    {
        return $this->belongsTo(Tahun::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function tahapan()
    {
        return $this->hasMany(KegiatanTahapan::class)->orderBy('urutan');
    }

    public function dokumen()
    {
        return $this->hasMany(KegiatanDokumen::class)->orderBy('urutan');
    }

    public function komponen()
    {
        return $this->hasMany(KomponenPenilaian::class)->orderBy('urutan');
    }

    public function grade()
    {
        return $this->hasMany(KegiatanGrade::class)->orderBy('urutan');
    }

    public function getStatusAttribute(): string
    {
        $now = now()->startOfDay();
        if ($now->lt($this->kegiatan_mulai)) return 'akan_datang';
        if ($now->gt($this->kegiatan_selesai)) return 'selesai';
        return 'berlangsung';
    }
}
