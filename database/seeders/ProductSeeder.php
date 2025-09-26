<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\product as Product;
use App\Models\product_image as ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create products with multiple images
        Product::factory(20)->create()->each(function ($product) {
            // Create primary image
            ProductImage::factory()->primary()->create([
                'product_id' => $product->id,
            ]);
            
            // Create 2-4 additional images
            ProductImage::factory(rand(2, 4))->create([
                'product_id' => $product->id,
            ]);
        });
    }
}
