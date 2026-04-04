<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'parent_id', 'label', 'url', 'icon', 'urutan', 'target', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan'    => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('urutan');
    }

    /**
     * Ambil semua menu top-level aktif beserta sub-menu aktif untuk ditampilkan.
     */
    public static function activeNav()
    {
        return static::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('urutan')
            ->with(['children' => fn($q) => $q->where('is_active', true)->orderBy('urutan')])
            ->get();
    }
}
