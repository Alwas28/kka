<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaLevel extends Model
{
    protected $table = 'mahasiswa_level';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = ['id', 'nama', 'keterangan'];

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'mahasiswa_level_id');
    }
}
