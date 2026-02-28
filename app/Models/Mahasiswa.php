<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Mahasiswa extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'nim',
        'nama',
        'email',
        'program_studi_id',
        'mahasiswa_level_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function level()
    {
        return $this->belongsTo(MahasiswaLevel::class, 'mahasiswa_level_id');
    }

    public function pendaftaran()
    {
        return $this->hasOne(MahasiswaPendaftaran::class, 'mahasiswa_id');
    }

    public function notifikasi()
    {
        return $this->hasMany(MahasiswaNotifikasi::class, 'mahasiswa_id');
    }

    public function unreadNotifikasi()
    {
        return $this->notifikasi()->whereNull('read_at');
    }

    public function kelompok()
    {
        return $this->belongsToMany(SurveyLokasi::class, 'kelompok_mahasiswa', 'mahasiswa_id', 'survey_lokasi_id')
            ->withPivot('is_koordinator')
            ->withTimestamps();
    }
}
