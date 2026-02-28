<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKka extends Model
{
    use HasFactory;

    protected $table = 'jenis_kka';

    protected $fillable = [
        'nama',
        'keterangan',
    ];
}
