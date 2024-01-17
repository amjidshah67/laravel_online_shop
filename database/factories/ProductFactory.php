<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = fake()->unique()->name();
        $slug = str::slug($title);
        $subCategories = [30,31];
        $subCateRandkey = array_rand($subCategories);

        $brands = [28,29,30,32];
        $brandsRandkey = array_rand($brands);
        return [
            'title' => $title,
            'slug' => $slug,
            'category_id' => 96,
            'sub_category_id' => $subCategories[$subCateRandkey],
            'brand_id' => $brands[$brandsRandkey],
            'price' => rand(10,1000),
            'sku' => rand(1,1000),
            'track_qty' => 'Yes',
            'qty' => 10,
            'is_featured' => 'Yes',
            'status' => 1,
        ];
    }
}
