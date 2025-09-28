<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\product as Product;
use App\Models\category as Category;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Home', [
            'heroSlides' => $this->getHeroSlides(),
            'bestSellers' => $this->getBestSellers(),
            'categories' => $this->getCategories(),
        ]);
    }

    private function getHeroSlides()
    {
        // Get categories from database for hero slides
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        if ($categories->isEmpty()) {
            // Return fallback data if no categories exist
            return $this->getFallbackHeroSlides();
        }

        $slides = [];
        $slideId = 1;

        foreach ($categories as $category) {
            $slides[] = [
                'id' => $slideId++,
                'title' => $this->getHeroTitle($category->slug),
                'description' => $this->getHeroDescription($category->slug),
                'badge' => $this->getHeroBadge($category->slug),
                'image' => $this->getHeroImage($category->slug),
                'primaryButton' => [
                    'text' => $this->getPrimaryButtonText($category->slug),
                    'href' => route('categories.show', $category->slug),
                ],
                'secondaryButton' => $this->getSecondaryButton($category->slug),
            ];
        }

        return $slides;
    }

    private function getBestSellers()
    {
        // Get featured products from database
        $products = Product::with(['primaryImage', 'images', 'category'])
            ->where('is_active', true)
            ->where('in_stock', true)
            ->where('is_featured', true)
            ->orderBy('reviews_count', 'desc')
            ->take(8)
            ->get();

        if ($products->isEmpty()) {
            // Get any products if no featured ones exist
            $products = Product::with(['primaryImage', 'images', 'category'])
                ->where('is_active', true)
                ->where('in_stock', true)
                ->orderBy('reviews_count', 'desc')
                ->take(8)
                ->get();
        }

        if ($products->isEmpty()) {
            return $this->getSampleBestSellers();
        }

        return $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'image' => $this->getProductImageUrl($product),
                'currentPrice' => $product->current_price,
                'originalPrice' => $product->original_price,
                'rating' => $product->rating,
                'reviewsCount' => $product->reviews_count,
                'salePercentage' => $product->sale_percentage,
                'inStock' => $product->in_stock,
                'category' => [
                    'name' => $product->category->name,
                    'slug' => $product->category->slug,
                ],
            ];
        });
    }

    private function getCategories()
    {
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        if ($categories->isEmpty()) {
            return $this->getSampleCategories();
        }

        return $categories->map(function ($category) {
            $productCount = Product::where('category_id', $category->id)
                ->where('is_active', true)
                ->count();

            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'image' => $this->getCategoryImageUrl($category),
                'href' => route('categories.show', $category->slug),
                'productCount' => $productCount,
            ];
        });
    }

    // Hero slide content based on category
    private function getHeroTitle($categorySlug)
    {
        $titles = [
            'traditional-bow' => 'Traditional Bows',
            'arrow' => 'Premium Arrows',
            'archery-accessories' => 'Archery Accessories',
        ];

        return $titles[$categorySlug] ?? 'Quality Archery Equipment';
    }

    private function getHeroDescription($categorySlug)
    {
        $descriptions = [
            'traditional-bow' => 'Discover our collection of authentic traditional recurve bows, meticulously crafted using time-honored methods passed down through generations.',
            'arrow' => 'Complete your archery setup with our range of high-quality arrows designed for accuracy, performance, and traditional archery excellence.',
            'archery-accessories' => 'Enhance your archery experience with our comprehensive collection of accessories and equipment crafted for serious archers.',
        ];

        return $descriptions[$categorySlug] ?? 'Experience the finest in traditional archery equipment.';
    }

    private function getHeroBadge($categorySlug)
    {
        $badges = [
            'traditional-bow' => 'New Collection',
            'arrow' => 'Premium Quality',
            'archery-accessories' => 'Essential Gear',
        ];

        return $badges[$categorySlug] ?? 'Featured';
    }

    private function getHeroImage($categorySlug)
    {
        $images = [
            'traditional-bow' => 'hero/hero-bow-1.jpg',
            'arrow' => 'hero/hero-arrows.jpg',
            'archery-accessories' => 'hero/hero-accessories.jpg',
        ];

        return $this->getImageUrl($images[$categorySlug] ?? 'hero/hero-default.jpg');
    }

    private function getPrimaryButtonText($categorySlug)
    {
        $buttonTexts = [
            'traditional-bow' => 'Shop Bows',
            'arrow' => 'Explore Arrows',
            'archery-accessories' => 'Shop Accessories',
        ];

        return $buttonTexts[$categorySlug] ?? 'Shop Now';
    }

    private function getSecondaryButton($categorySlug)
    {
        // Only show secondary button for traditional-bow
        if ($categorySlug === 'traditional-bow') {
            return [
                'text' => 'Learn More',
                'href' => '/about',
            ];
        }

        return null;
    }

    // Image URL helpers
    private function getProductImageUrl($product)
    {
        if ($product->primaryImage) {
            return $this->getImageUrl($product->primaryImage->image_path);
        }

        if ($product->images->first()) {
            return $this->getImageUrl($product->images->first()->image_path);
        }

        return asset('images/products/placeholder.jpg');
    }

    private function getCategoryImageUrl($category)
    {
        if ($category->image) {
            return $this->getImageUrl($category->image);
        }

        return asset('images/categories/' . $category->slug . '.jpg');
    }

    private function getImageUrl($path)
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }
        return asset('images/' . $path);
    }

    // Fallback methods for when database is empty
    private function getFallbackHeroSlides()
    {
        return [
            [
                'id' => 1,
                'title' => 'Traditional Bows',
                'description' => 'Discover our collection of authentic traditional recurve bows, meticulously crafted using time-honored methods.',
                'badge' => 'New Collection',
                'image' => asset('images/hero/hero-bow-1.jpg'),
                'primaryButton' => [
                    'text' => 'Shop Bows',
                    'href' => '/categories/traditional-bow',
                ],
                'secondaryButton' => [
                    'text' => 'Learn More',
                    'href' => '/about',
                ],
            ],
            [
                'id' => 2,
                'title' => 'Premium Arrows',
                'description' => 'Complete your archery setup with our range of high-quality arrows designed for accuracy and performance.',
                'badge' => 'Premium Quality',
                'image' => asset('images/hero/hero-arrows.jpg'),
                'primaryButton' => [
                    'text' => 'Explore Arrows',
                    'href' => '/categories/arrow',
                ],
            ],
            [
                'id' => 3,
                'title' => 'Archery Accessories',
                'description' => 'Enhance your archery experience with our comprehensive collection of accessories and equipment.',
                'badge' => 'Essential Gear',
                'image' => asset('images/hero/hero-accessories.jpg'),
                'primaryButton' => [
                    'text' => 'Shop Accessories',
                    'href' => '/categories/archery-accessories',
                ],
            ],
        ];
    }

    private function getSampleBestSellers()
    {
        return [
            [
                'id' => 1,
                'name' => 'Ottoman Recurve Bow',
                'slug' => 'ottoman-recurve-bow',
                'image' => asset('images/products/sample-bow.jpg'),
                'currentPrice' => 299.99,
                'originalPrice' => 349.99,
                'rating' => 4.8,
                'reviewsCount' => 24,
                'salePercentage' => 15,
                'inStock' => true,
            ],
            [
                'id' => 2,
                'name' => 'Carbon Arrows Set',
                'slug' => 'carbon-arrows-set',
                'image' => asset('images/products/sample-arrows.jpg'),
                'currentPrice' => 49.99,
                'originalPrice' => 59.99,
                'rating' => 4.6,
                'reviewsCount' => 18,
                'salePercentage' => 17,
                'inStock' => true,
            ],
        ];
    }

    private function getSampleCategories()
    {
        return [
            [
                'id' => 1,
                'name' => 'Traditional Bow',
                'slug' => 'traditional-bow',
                'description' => 'Authentic traditional and recurve bows',
                'image' => asset('images/categories/bows.jpg'),
                'href' => '/categories/traditional-bow',
                'productCount' => 12,
            ],
            [
                'id' => 2,
                'name' => 'Arrow',
                'slug' => 'arrow',
                'description' => 'High-quality arrows for all bow types',
                'image' => asset('images/categories/arrows.jpg'),
                'href' => '/categories/arrow',
                'productCount' => 8,
            ],
            [
                'id' => 3,
                'name' => 'Archery Accessories',
                'slug' => 'archery-accessories',
                'description' => 'Essential archery equipment and accessories',
                'image' => asset('images/categories/accessories.jpg'),
                'href' => '/categories/archery-accessories',
                'productCount' => 15,
            ],
        ];
    }
}