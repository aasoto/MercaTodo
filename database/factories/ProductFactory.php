<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
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
        return [
            'name' => fake()->words(4, true),
            'slug' => fake()->slug(4),
            'products_category_id' => ProductCategory::select('id')->inRandomOrder()->first(),
            'barcode' => fake()->randomNumber(5, true).fake()->randomNumber(5, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10000, 1000000),
            'unit' => $this->get_code_unit(),
            'stock' => fake()->randomNumber(2, true),
            // vendor\fakerphp\faker\src\Faker\Provider\Image.php
            'picture_1' => fake()->image(public_path('images/products'), 500, 500, null, false),
            'picture_2' => fake()->image(public_path('images/products'), 500, 500, null, false),
            'picture_3' => fake()->image(public_path('images/products'), 500, 500, null, false),
        ];
    }

    public function get_code_unit(): string
    {
        $unit = Unit::select('code')->inRandomOrder()->first();
        return $unit['code'];
    }
}
