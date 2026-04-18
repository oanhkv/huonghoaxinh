<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BlogPost;

class BlogCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function posts()
    {
        return $this->hasMany(BlogPost::class);
    }
}
