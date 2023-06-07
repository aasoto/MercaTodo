<?php

namespace Database\Factories;

use App\Domain\User\Models\TypeDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TypeDocument>
 */
class TypeDocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = TypeDocument::class;

    public function definition(): array
    {
        return [
            'code' => fake()->lexify('???'),
            'name' => fake()->words(2, true),
        ];
    }
}
