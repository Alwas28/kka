<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Berita extends Model
{
    protected $table = 'berita';

    protected $fillable = [
        'user_id',
        'judul',
        'slug',
        'konten',
        'gambar',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /** Generate slug unik dari judul */
    public static function generateSlug(string $judul, ?int $excludeId = null): string
    {
        $slug = Str::slug($judul);
        $original = $slug;
        $i = 1;
        while (
            static::where('slug', $slug)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $original . '-' . $i++;
        }
        return $slug;
    }
}
