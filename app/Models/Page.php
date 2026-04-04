<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $table = 'pages';

    protected $fillable = [
        'user_id', 'judul', 'slug', 'konten', 'meta_description', 'gambar', 'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateSlug(string $judul, ?int $excludeId = null): string
    {
        $slug     = Str::slug($judul);
        $original = $slug;
        $count    = 1;

        while (
            static::where('slug', $slug)
                  ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                  ->exists()
        ) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}
