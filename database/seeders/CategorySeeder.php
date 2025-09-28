<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\product_image as ProductImage; // Keep your alias
use App\Models\category as Category;          // Keep your alias
use App\Models\product as Product;            // Keep your alias

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create your 3 main categories
        $categories = [
            [
                'name' => 'Traditional Bow',
                'slug' => 'traditional-bow',
                'description' => 'Authentic traditional and recurve bows crafted with precision and heritage techniques',
                'sort_order' => 1,
            ],
            [
                'name' => 'Arrow',
                'slug' => 'arrow',
                'description' => 'High-quality arrows designed for accuracy, performance, and traditional archery',
                'sort_order' => 2,
            ],
            [
                'name' => 'Archery Accessories',
                'slug' => 'archery-accessories',
                'description' => 'Essential archery equipment and accessories to enhance your shooting experience',
                'sort_order' => 3,
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);

            // Create some sample products for each category
            $this->createSampleProducts($category);
        }
    }

    private function createSampleProducts(Category $category)
    {
        $productData = [];

        switch ($category->slug) {
            case 'traditional-bow':
                $productData = [
                    ['name' => 'Ottoman Recurve Bow', 'price' => 299.99, 'original_price' => 349.99],
                    ['name' => 'Hungarian Horse Bow', 'price' => 199.99],
                    ['name' => 'English Longbow', 'price' => 189.99],
                    ['name' => 'Turkish Composite Bow', 'price' => 399.99, 'original_price' => 449.99],
                    ['name' => 'Medieval Recurve Bow', 'price' => 249.99],
                ];
                break;

            case 'arrow':
                $productData = [
                    ['name' => 'Carbon Arrows Set (12pcs)', 'price' => 49.99, 'original_price' => 59.99],
                    ['name' => 'Wooden Traditional Arrows', 'price' => 39.99],
                    ['name' => 'War Arrows Historical Set', 'price' => 79.99],
                    ['name' => 'Target Arrows Competition', 'price' => 69.99, 'original_price' => 79.99],
                    ['name' => 'Bamboo Arrows Traditional', 'price' => 34.99],
                ];
                break;

            case 'archery-accessories':
                $productData = [
                    ['name' => 'Leather Quiver Traditional', 'price' => 89.99, 'original_price' => 99.99],
                    ['name' => 'Arm Guard Leather', 'price' => 24.99],
                    ['name' => 'Bow String Flemish', 'price' => 19.99],
                    ['name' => 'Finger Tab Shooting Glove', 'price' => 14.99],
                    ['name' => 'Arrow Nocks Pack (50pcs)', 'price' => 12.99, 'original_price' => 15.99],
                ];
                break;
        }

        foreach ($productData as $index => $data) {
            $product = Product::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'category_id' => $category->id,
                'description' => "High-quality {$data['name']} crafted with attention to detail and traditional methods.",
                'current_price' => $data['price'],
                'original_price' => $data['original_price'] ?? null,
                'stock' => rand(5, 50),
                'in_stock' => true,
                'rating' => rand(40, 50) / 10, // 4.0 to 5.0
                'reviews_count' => rand(5, 100),
                'sale_percentage' => isset($data['original_price']) ? 
                    round((($data['original_price'] - $data['price']) / $data['original_price']) * 100) : null,
                'is_active' => true,
                'is_featured' => $index < 2, // First 2 products are featured
            ]);

            // Create a primary image for each product
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => "products/{$category->slug}/" . Str::slug($data['name']) . '.jpg',
                'alt_text' => $data['name'],
                'sort_order' => 0,
                'is_primary' => true,
            ]);

            // Create additional images (2-3 per product)
            for ($i = 1; $i <= rand(2, 3); $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => "products/{$category->slug}/" . Str::slug($data['name']) . "-{$i}.jpg",
                    'alt_text' => "{$data['name']} - Image {$i}",
                    'sort_order' => $i,
                    'is_primary' => false,
                ]);
            }
        }
    }
}