<?php

namespace Tests\Feature\Domain\Product\Actions;

use App\Domain\Product\Actions\StoreProductAction;
use App\Domain\Product\Dtos\StoreProductData;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\Product\Models\Unit;
use App\Domain\Product\Services\ImagesServices;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreProductActionTest extends TestCase
{
    use RefreshDatabase;

    private ProductCategory $category;
    private Unit $unit;
    private UploadedFile $image;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        ProductCategory::factory()->create();
        Unit::factory()->create();

        $this->category = ProductCategory::select('id')->inRandomOrder()->first();
        $this->unit = Unit::select('code')->inRandomOrder()->first();
        $this->image = UploadedFile::fake()->image('fotoPrueba.png', 500, 500)->size(500);
    }

    public function test_can_store_product(): void
    {
        $data = new StoreProductData(
            name: fake()->words(4, true),
            slug: fake()->slug(4),
            products_category_id: $this->category['id'],
            barcode: fake()->randomNumber(5, true).fake()->randomNumber(5, true),
            description: fake()->paragraph(),
            price: fake()->randomFloat(2, 10000, 1000000),
            unit: $this->unit['code'],
            stock: fake()->randomNumber(2, true),
            picture_1: $this->image,
            picture_2: $this->image,
            picture_3: $this->image,
            availability: true,
        );

        $product = (new StoreProductAction(new ImagesServices()))->handle($data);

        $this->assertEquals($data->name, $product['name']);
        $this->assertEquals($data->price, $product['price']);

        $this->assertDatabaseHas('products', [
            'name' => $data->name,
            'price' => $data->price,
        ]);
    }
}
