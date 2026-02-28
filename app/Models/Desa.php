<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    protected $table = 'desa';

    protected $fillable = ['kecamatan_id', 'kode', 'nama'];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
}
