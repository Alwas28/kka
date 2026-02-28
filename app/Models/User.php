<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function programStudi()
    {
        return $this->belongsToMany(ProgramStudi::class, 'user_program_studi');
    }

    /**
     * Kumpulkan semua nama access dari seluruh role user (di-cache per request).
     */
    public function getAllAccesses(): \Illuminate\Support\Collection
    {
        if (!isset($this->_cachedAccesses)) {
            $this->_cachedAccesses = $this->roles()
                ->with('accesses')
                ->get()
                ->flatMap->accesses
                ->pluck('nama')
                ->unique();
        }

        return $this->_cachedAccesses;
    }

    /**
     * Cek apakah user memiliki access tertentu.
     */
    public function hasAccess(string $nama): bool
    {
        return $this->getAllAccesses()->contains($nama);
    }
}
