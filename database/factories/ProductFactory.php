<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);
        $isFree = fake()->boolean(35);

        return [
            'product_category_id' => ProductCategory::factory(),
            'title' => str($title)->title()->toString(),
            'slug' => Str::slug($title),
            'short_description' => fake()->sentence(8),
            'description' => fake()->paragraphs(2, true),
            'thumbnail' => null,
            'price' => $isFree ? 0 : fake()->numberBetween(2500, 45000),
            'price_type' => $isFree ? Product::PRICE_TYPE_FREE : Product::PRICE_TYPE_PAID,
            'currency' => 'NGN',
            'product_type' => fake()->randomElement([Product::PRODUCT_TYPE_DIGITAL, Product::PRODUCT_TYPE_PHYSICAL]),
            'is_free' => $isFree,
            'is_new_release' => fake()->boolean(40),
            'is_featured' => fake()->boolean(30),
            'is_active' => true,
            'display_order' => fake()->numberBetween(0, 20),
        ];
    }
}
