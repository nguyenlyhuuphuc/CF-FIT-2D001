<?php

namespace Database\Factories;

use App\Models\ProductCategory;
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
    public function definition(): array
    {
        $price = fake()->numberBetween(20, 900);
        $originPrice = $price - 10;
        $discountPrice = $price * 0.9;

        $name = fake()->name();
        $slug = Str::slug($name);

        return [
            'name' => $name,
            'price' => $price,
            'origin_price' => $originPrice,
            'discount_price' => $discountPrice,
            'short_description' => fake()->text('100'),
            'qty' => fake()->numberBetween(10, 100),
            'shipping' => fake()->numberBetween(10, 100),
            'weight' => fake()->numberBetween(10, 100),
            'description' => fake()->randomHtml,
            'information' => fake()->randomHtml(),
            'review' => fake()->randomHtml(),
            'image' => null,
            'slug' => $slug,
            'product_category_id' => fake()->randomElement(ProductCategory::pluck('id')), 
        ];
    }
}
