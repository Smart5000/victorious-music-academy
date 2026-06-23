<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Keyboard', 'description' => 'Keyboards and piano learning tools.'],
            ['name' => 'Books', 'description' => 'Music books for young learners.'],
            ['name' => 'PDFs', 'description' => 'Downloadable practice materials.'],
            ['name' => 'Free Materials', 'description' => 'Free guides and resources for students.'],
            ['name' => 'New Release', 'description' => 'Fresh academy learning products.'],
        ])->mapWithKeys(function (array $category) {
            $record = ProductCategory::query()->updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    ...$category,
                    'slug' => Str::slug($category['name']),
                    'is_active' => true,
                ],
            );

            return [$record->name => $record];
        });

        collect([
            [
                'category' => 'Free Materials',
                'title' => 'Beginner Practice Checklist',
                'short_description' => 'A simple weekly guide to help children practice with confidence.',
                'price' => 0,
                'price_type' => Product::PRICE_TYPE_FREE,
                'product_type' => Product::PRODUCT_TYPE_MATERIALS,
                'is_free' => true,
                'is_new_release' => false,
                'display_order' => 1,
            ],
            [
                'category' => 'PDFs',
                'title' => 'Keyboard Finger Numbers PDF',
                'short_description' => 'Printable finger number chart for early keyboard lessons.',
                'price' => 0,
                'price_type' => Product::PRICE_TYPE_FREE,
                'product_type' => Product::PRODUCT_TYPE_MATERIALS,
                'is_free' => true,
                'is_new_release' => true,
                'display_order' => 2,
            ],
            [
                'category' => 'Books',
                'title' => 'Little Music Star Workbook',
                'short_description' => 'A friendly workbook for notes, rhythm, and practice habits.',
                'price' => 5000,
                'price_type' => Product::PRICE_TYPE_PAID,
                'product_type' => Product::PRODUCT_TYPE_INSTRUMENT,
                'is_free' => false,
                'is_new_release' => true,
                'display_order' => 3,
            ],
            [
                'category' => 'Keyboard',
                'title' => 'Starter Keyboard Guide',
                'short_description' => 'A parent-friendly guide for choosing a first keyboard.',
                'price' => 3500,
                'price_type' => Product::PRICE_TYPE_PAID,
                'product_type' => Product::PRODUCT_TYPE_MATERIALS,
                'is_free' => false,
                'is_new_release' => true,
                'display_order' => 4,
            ],
            [
                'category' => 'PDFs',
                'title' => 'Daily Chord Practice Cards',
                'short_description' => 'Printable cards for simple chord practice at home.',
                'price' => 2500,
                'price_type' => Product::PRICE_TYPE_PAID,
                'product_type' => Product::PRODUCT_TYPE_MATERIALS,
                'is_free' => false,
                'is_new_release' => false,
                'display_order' => 5,
            ],
        ])->each(function (array $product) use ($categories) {
            $category = $categories[$product['category']];

            Product::query()->updateOrCreate(
                ['slug' => Str::slug($product['title'])],
                [
                    'product_category_id' => $category->id,
                    'title' => $product['title'],
                    'slug' => Str::slug($product['title']),
                    'short_description' => $product['short_description'],
                    'description' => $product['short_description'].' This material supports children as they grow in confidence, creativity, and musical understanding.',
                    'thumbnail' => null,
                    'price' => $product['price'],
                    'price_type' => $product['price_type'],
                    'currency' => 'NGN',
                    'product_type' => $product['product_type'],
                    'is_free' => $product['is_free'],
                    'is_new_release' => $product['is_new_release'],
                    'is_featured' => true,
                    'is_active' => true,
                    'display_order' => $product['display_order'],
                ],
            );
        });
    }
}
