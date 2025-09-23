<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Home', [
            'bestSellers' => $this->getBestSellers(),
            'categories' => $this->getCategories(),
            'heroSlides' => $this->getHeroSlides(),
        ]);
    }

    private function getBestSellers()
    {
        // In a real application, this would fetch from database
        return [
            [
                'id' => 1,
                'name' => 'Bora S+ / Ottoman Hybrid Bow',
                'slug' => 'bora-s-ottoman-hybrid-bow',
                'current_price' => 330.00,
                'original_price' => 380.00,
                'sale_percentage' => 13,
                'rating' => 4.8,
                'reviews_count' => 24,
                'in_stock' => true,
                'image' => '/images/products/bora-bow.jpg',
                'variations' => [
                    ['id' => 1, 'type' => 'Draw Weight', 'name' => '30-34 lbs'],
                    ['id' => 2, 'type' => 'Draw Weight', 'name' => '35-39 lbs'],
                    ['id' => 3, 'type' => 'Draw Weight', 'name' => '40-44 lbs'],
                ]
            ],
            [
                'id' => 2,
                'name' => 'Koç Nocks Small / 50 Pieces',
                'slug' => 'koc-nocks-small-50-pieces',
                'current_price' => 50.00,
                'rating' => 4.9,
                'reviews_count' => 18,
                'in_stock' => true,
                'image' => '/images/products/nocks.jpg',
            ],
            [
                'id' => 3,
                'name' => 'Sungur Carbon Arrow, 400 spine, 12 Pieces',
                'slug' => 'sungur-carbon-arrow-400-spine',
                'current_price' => 108.00,
                'rating' => 4.7,
                'reviews_count' => 32,
                'in_stock' => true,
                'image' => '/images/products/carbon-arrows.jpg',
                'variations' => [
                    ['id' => 1, 'type' => 'Length', 'name' => '26"'],
                    ['id' => 2, 'type' => 'Length', 'name' => '28"'],
                    ['id' => 3, 'type' => 'Length', 'name' => '30"'],
                ]
            ]
        ];
    }

    private function getCategories()
    {
        return [
            [
                'id' => 1,
                'name' => 'Archery Equipment',
                'description' => 'Traditional bows, arrows, and accessories',
                'image' => '/images/categories/archery.jpg',
                'href' => '/products/archery-equipment',
                'productCount' => 125
            ],
            [
                'id' => 2,
                'name' => 'Historical Clothes',
                'description' => 'Feel the history on yourself',
                'image' => '/images/categories/historical-clothes.jpg',
                'href' => '/products/historical-clothes',
                'productCount' => 85
            ],
            [
                'id' => 3,
                'name' => 'Equestrian Equipment',
                'description' => 'Professional horseback archery gear',
                'image' => '/images/categories/equestrian.jpg',
                'href' => '/products/equestrian',
                'productCount' => 45
            ]
        ];
    }

    private function getHeroSlides()
    {
        return [
            [
                'id' => 1,
                'badge' => 'New Collection',
                'title' => 'Traditional Ottoman Bows',
                'description' => 'Experience the power and precision of authentic Ottoman archery with our handcrafted traditional bows.',
                'image' => '/images/hero-bow-1.jpg',
                'primaryButton' => [
                    'text' => 'Shop Now',
                    'href' => '/products/bows'
                ],
                'secondaryButton' => [
                    'text' => 'Learn More',
                    'href' => '/about'
                ]
            ],
            [
                'id' => 2,
                'badge' => 'Premium Quality',
                'title' => 'Historical Clothing',
                'description' => 'Feel the history on yourself with our authentic historical clothing collection.',
                'image' => '/images/hero-clothing.jpg',
                'primaryButton' => [
                    'text' => 'Explore Collection',
                    'href' => '/products/historical-clothes'
                ]
            ],
            [
                'id' => 3,
                'badge' => 'Professional Grade',
                'title' => 'Equestrian Equipment',
                'description' => 'Complete your horseback archery experience with our professional-grade equipment.',
                'image' => '/images/hero-equestrian.jpg',
                'primaryButton' => [
                    'text' => 'View Equipment',
                    'href' => '/products/equestrian'
                ]
            ]
        ];
    }
}