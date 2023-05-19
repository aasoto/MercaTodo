<?php

namespace Database\Factories;

use App\Domain\Product\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Unit::class;

    public function definition(): array
    {
        return [
            'code' => fake()->lexify('????'),
            'name' => fake()->words(4, true),
        ];
    }
}
