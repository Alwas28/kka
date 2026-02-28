<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'keterangan',
    ];

    public function accesses()
    {
        return $this->belongsToMany(Access::class, 'role_access');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role');
    }
}
