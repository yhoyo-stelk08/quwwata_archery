<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraphs(3, true),
            'current_price' => $this->faker->randomFloat(2, 50, 500),
            'original_price' => $this->faker->optional(0.3)->randomFloat(2, 60, 600),
            'stock' => $this->faker->numberBetween(0, 100),
            'in_stock' => $this->faker->boolean(85),
            'rating' => $this->faker->randomFloat(1, 3, 5),
            'reviews_count' => $this->faker->numberBetween(0, 150),
            'sale_percentage' => $this->faker->optional(0.3)->numberBetween(5, 50),
        ];
    }
}
