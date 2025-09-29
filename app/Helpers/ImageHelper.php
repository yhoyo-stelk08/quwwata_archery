<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get image URL with fallback to placeholder
     */
    public static function getImageUrl(?string $path, string $type = 'product'): string
    {
        // If no path provided, return placeholder
        if (!$path) {
            return asset("images/placeholders/placeholder-{$type}.jpg");
        }
        
        // If file exists in storage, return storage URL
        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }
        
        // If file exists in public directory, return asset URL
        if (file_exists(public_path($path))) {
            return asset($path);
        }
        
        // Fallback to placeholder
        return asset("images/placeholders/placeholder-{$type}.jpg");
    }
    
    /**
     * Store uploaded image
     */
    public static function storeImage($file, string $directory): string
    {
        return $file->store($directory, 'public');
    }
    
    /**
     * Delete image from storage
     */
    public static function deleteImage(?string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        
        return false;
    }
}