<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'price',
        'compare_at_price',
        'inventory',
        'is_featured',
        'status',
        'hero_image',
        'meta_title',
        'meta_description',
        'meta_image',
        'specifications',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'specifications' => 'array',
        'published_at' => 'datetime',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps()->withPivot('sort_order');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->published()->where('is_featured', true);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getHeroImageUrlAttribute(): ?string
    {
        if (! $this->hero_image) {
            return null;
        }

        if (Str::startsWith($this->hero_image, ['http://', 'https://'])) {
            return $this->hero_image;
        }

        if (Str::startsWith($this->hero_image, ['storage', 'images', '/images'])) {
            return asset(ltrim($this->hero_image, '/'));
        }

        return asset('storage/' . ltrim($this->hero_image, '/'));
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2);
    }
}
