<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\product_image as ProductImage;

class product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'current_price',
        'original_price',
        'stock',
        'in_stock',
        'rating',
        'reviews_count',
        'sale_percentage',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'current_price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'rating' => 'float',
        'in_stock' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the category that owns the product
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all images for the product
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * Get the primary image for the product
     */
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Get the first image (fallback if no primary image)
     */
    public function firstImage()
    {
        return $this->hasOne(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for in stock products
     */
    public function scopeInStock($query)
    {
        return $query->where('in_stock', true);
    }

    /**
     * Get the main image URL
     */
    public function getMainImageUrlAttribute()
    {
        $primaryImage = $this->primaryImage ?? $this->firstImage;
        
        if ($primaryImage && Storage::disk('public')->exists($primaryImage->image_path)) {
            return Storage::url($primaryImage->image_path);
        }
        
        return asset('images/placeholders/placeholder-product.jpg');
    }

    /**
     * Get all image URLs
     */
    public function getImageUrlsAttribute()
    {
        return $this->images->map(function ($image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                return Storage::url($image->image_path);
            }
            return asset('images/placeholders/placeholder-product.jpg');
        });
    }

    /**
     * Get category breadcrumb
     */
    public function getCategoryBreadcrumbAttribute()
    {
        return $this->category ? $this->category->breadcrumb : collect();
    }
}
