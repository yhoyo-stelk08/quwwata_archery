<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\carousel_slide as CarouselSlide;

class CarouselSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CarouselSlide::create([
            'title' => 'Traditional Bows',
            'description' => 'Discover our collection of authentic traditional recurve bows, meticulously crafted using time-honored methods passed down through generations.',
            'badge' => 'Traditional Bows',
            'image' => null, // Will use default image from model
            'primary_button_text' => 'Shop Bows',
            'primary_button_url' => '/categories/traditional-bows',
            'secondary_button_text' => 'Learn More',
            'secondary_button_url' => '/about',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        CarouselSlide::create([
            'title' => 'Premium Arrows',
            'description' => 'Experience precision and consistency with our handcrafted arrows, designed for traditional archery enthusiasts and competitive shooters.',
            'badge' => 'Premium Quality',
            'image' => null,
            'primary_button_text' => 'Shop Arrows',
            'primary_button_url' => '/categories/arrows',
            'secondary_button_text' => null,
            'secondary_button_url' => null,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        CarouselSlide::create([
            'title' => 'Archery Accessories',
            'description' => 'Complete your traditional archery setup with our carefully selected accessories, from arm guards to quivers and targets.',
            'badge' => 'Complete Your Setup',
            'image' => null,
            'primary_button_text' => 'View Accessories',
            'primary_button_url' => '/categories/accessories',
            'secondary_button_text' => 'Contact Us',
            'secondary_button_url' => '/contact',
            'sort_order' => 3,
            'is_active' => true,
        ]);
    }
}
