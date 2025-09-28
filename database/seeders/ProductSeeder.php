<?php

namespace Database\Seeders;

use App\Models\product as Product;
use App\Models\category as Category;
use App\Models\product_image as ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing categories
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            $this->command->error('No categories found! Run CategorySeeder first.');
            return;
        }

        // Create products for each category
        $categories->each(function ($category) {
            Product::factory(rand(3, 8))->create([
                'category_id' => $category->id,
            ])->each(function ($product) {
                // Create primary image
                ProductImage::factory()->primary()->create([
                    'product_id' => $product->id,
                ]);
                
                // Create additional images
                ProductImage::factory(rand(2, 4))->create([
                    'product_id' => $product->id,
                ]);
            });
        });
    }
}