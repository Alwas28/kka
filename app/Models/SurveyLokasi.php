<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyLokasi extends Model
{
    use HasFactory;

    protected $table = 'survey_lokasi';

    protected $fillable = [
        'desa_id',
        'surveyor_id',
        'kegiatan_id',
        'tim_anggota',
        'kelompok',
        'status',
        'nama_kades',
        'no_hp_kades',
        'pemberi_informasi',
        'rencana_posko',
        'rencana_posko_lainnya',
        'kondisi_air',
        'kondisi_listrik',
        'kondisi_transportasi',
        'deskripsi',
        'gmaps_url',
        'rekomendasi',
        'alasan_rekomendasi',
        'disetujui',
        'catatan_panitia',
        'surveyed_at',
        'approved_at',
    ];

    protected $casts = [
        'rekomendasi' => 'boolean',
        'disetujui'   => 'boolean',
        'surveyed_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function surveyor()
    {
        return $this->belongsTo(User::class, 'surveyor_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function peserta()
    {
        return $this->belongsToMany(Mahasiswa::class, 'kelompok_mahasiswa')
                    ->withPivot('is_koordinator')
                    ->withTimestamps();
    }

    public function dosenPembimbing()
    {
        return $this->belongsToMany(Pegawai::class, 'kelompok_dosen')
                    ->withTimestamps();
    }

    /**
     * Cek apakah waktu pengisian survey masih dalam periode yang ditentukan
     * di tahapan 'survey' kegiatan. Jika kegiatan / tahapan tidak diset → bebas.
     */
    public function isSurveyPeriodOpen(): bool
    {
        $kegiatan = $this->kegiatan;
        if (!$kegiatan) return true;

        $tahapan = $kegiatan->tahapan->firstWhere('nama', 'survey');
        if (!$tahapan || (!$tahapan->mulai && !$tahapan->selesai)) return true;

        $now = now()->startOfDay();
        $mulai   = $tahapan->mulai;
        $selesai = $tahapan->selesai;

        if ($mulai && $now->lt($mulai)) return false;
        if ($selesai && $now->gt($selesai)) return false;

        return true;
    }

    /**
     * Kembalikan pesan periode survey (untuk tampilan di view).
     */
    public function getSurveyPeriodInfo(): ?string
    {
        $kegiatan = $this->kegiatan;
        if (!$kegiatan) return null;

        $tahapan = $kegiatan->tahapan->firstWhere('nama', 'survey');
        if (!$tahapan || (!$tahapan->mulai && !$tahapan->selesai)) return null;

        $mulai   = $tahapan->mulai?->format('d/m/Y');
        $selesai = $tahapan->selesai?->format('d/m/Y');

        return match(true) {
            $mulai && $selesai => "{$mulai} – {$selesai}",
            $mulai             => "mulai {$mulai}",
            default            => "s.d. {$selesai}",
        };
    }

    public function getLokasiLengkapAttribute()
    {
        $desa = $this->desa;
        if (!$desa) return '-';
        $kec = $desa->kecamatan;
        $kab = $kec?->kabupaten;
        $prov = $kab?->provinsi;
        return collect([$desa->nama, $kec?->nama, $kab?->nama, $prov?->nama])->filter()->implode(', ');
    }
}
