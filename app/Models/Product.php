<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, LogsActivity, HasSlug, InteractsWithMedia;
    protected $fillable = [
        'brand_id',
        'name',
        'description',
        'price',
        'status'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }

    public function features()
    {
        return $this->hasMany(Feature::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRating()
    {
        $total_count = $this->reviews()->count();
        $total_rating = $this->reviews->sum('rating');
        if ($total_count > 0)
            return $total_rating / $total_count;
        return 0.00;
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }

    public function getPrimaryImageAttribute()
    {
        $images = $this->images->where('is_primary', 1);
        // return $images;
        if ($images && $images->count() > 0) {
            $primary_image = $images->first()->image;
        } else {
            $images = $this->images->first();
            if ($images && $images->count() > 0) {
                $primary_image = $images->image;
            } else {
                return null;
            }
        }
        return asset('storage/products/' . $primary_image);
    }
}
