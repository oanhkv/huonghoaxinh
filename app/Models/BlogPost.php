<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BlogCategory;

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
}
