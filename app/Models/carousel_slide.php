<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class carousel_slide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'badge',
        'image',
        'primary_button_text',
        'primary_button_url',
        'secondary_button_text',
        'secondary_button_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getImageUrlAttribute(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            // Return absolute URL instead of relative
            return url(Storage::url($this->image));
        }
        
        // Return default images based on slide content
        $defaultImages = [
            'traditional bows' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop&auto=format',
            'arrows' => 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=800&h=600&fit=crop&auto=format',
            'accessories' => 'https://images.unsplash.com/photo-1516981879613-9177bb5ad9e7?w=800&h=600&fit=crop&auto=format',
        ];

        // Try to match based on title or description
        $content = strtolower($this->title . ' ' . $this->description);
        
        foreach ($defaultImages as $keyword => $url) {
            if (str_contains($content, $keyword)) {
                return $url;
            }
        }
        
        // Fallback default image
        return 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800&h=600&fit=crop&auto=format';
    }

    public static function getActiveSlides()
    {
        return self::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at')
            ->get();
    }
}
