<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    use HasFactory;

    protected $table = 'program_studi';

    protected $fillable = [
        'fakultas_id',
        'kode',
        'nama',
        'jenjang',
        'keterangan',
    ];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_program_studi');
    }
}
