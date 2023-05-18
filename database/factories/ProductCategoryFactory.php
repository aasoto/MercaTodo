<?php

namespace Database\Factories;

use App\Domain\Product\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ProductCategory::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true)
        ];
    }
}
