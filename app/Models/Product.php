<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'category_id',
        'is_featured',
        'is_active',
        'sizes',
        'colors',
        'materials',
    ];

    protected $casts = [
        'sizes' => 'array',
        'colors' => 'array',
        'materials' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function visibleReviews()
    {
        return $this->reviews()->where('is_visible', true);
    }

    public function getAverageRatingAttribute()
    {
        return round($this->visibleReviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewCountAttribute()
    {
        return $this->visibleReviews()->count();
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

        // Nếu chỉ nhập tên ảnh (vd: rose.jpg) thì ưu tiên lấy từ public/img.
        if (! str_contains($image, '/')) {
            return asset('img/'.$image);
        }

        // Mặc định ảnh upload bởi admin lưu ở storage/app/public.
        return asset('storage/'.$image);
    }
}
