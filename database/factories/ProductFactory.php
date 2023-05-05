<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

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
            // 'picture_1' => fake()->image(storage_path('app/public/images/products'), 500, 500, null, false),
            // 'picture_2' => fake()->image(storage_path('app/public/images/products'), 500, 500, null, false),
            // 'picture_3' => fake()->image(storage_path('app/public/images/products'), 500, 500, null, false),
            'picture_1' => $this->upload_image(),
            'picture_2' => $this->upload_image(),
            'picture_3' => $this->upload_image(),
            'availability' => true,
        ];
    }

    public function get_code_unit(): string
    {
        $unit = Unit::select('code')->inRandomOrder()->first();
        return $unit['code'];
    }

    public function upload_image(): string
    {
        $filename = fake()->numerify('##########').'.jpg';
        UploadedFile::fake()->image('image.jpg', 500, 500)->storeAs('images/products', $filename, 'public');
        return $filename;
    }
}
