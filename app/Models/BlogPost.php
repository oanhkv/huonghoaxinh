<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'blog_category_id',
        'excerpt',
        'content',
        'image',
        'is_active',
        'published_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getImageUrlAttribute(): ?string
    {
        $image = trim((string) $this->image);
        if ($image === '') {
            return null;
        }

        $image = str_replace('\\', '/', $image);
        $lower = strtolower($image);

        if (str_starts_with($lower, 'http://') || str_starts_with($lower, 'https://')) {
            return $image;
        }

        if (str_starts_with($image, '/')) {
            return asset(ltrim($image, '/'));
        }

        if (str_starts_with($lower, 'storage/') || str_starts_with($lower, 'img/')) {
            return asset($image);
        }

        if (! str_contains($image, '/')) {
            return asset('img/'.$image);
        }

        return asset('storage/'.$image);
    }
}
