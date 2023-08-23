<?php

namespace Tests\Feature\Http\Controllers\Api\Client;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\Product\Models\Unit;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ShowcaseControllerTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        ProductCategory::factory()->create();
        Unit::factory()->create();

        $this->product = Product::factory()->create([
            'price' => 89999.42
        ]);

        Product::factory()->count(3)->create();
    }

    public function test_can_list_products_showcase_from_api(): void
    {
        $response = $this->getJson(route('api.showcase.index'));

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 4)
            ->has('data.0', fn (AssertableJson $json) =>
                $json->where('id', $this->product->id)
                    ->where('name', $this->product->name)
                    ->where('barcode', $this->product->barcode)
                    ->where('description', $this->product->description)
                    ->etc()
            )
            ->has('links')
            ->has('meta')
        );
    }

    public function test_can_show_product_in_showcase_from_api(): void
    {
        $response = $this->getJson(route('api.showcase.show', $this->product->slug));

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->has('data', fn (AssertableJson $json) =>
                $json->where('name', $this->product->name)
                    ->where('barcode', $this->product->barcode)
                    ->where('description', $this->product->description)
                    ->where('price', $this->product->price)
                    ->etc()
            )
        );
    }

    public function test_can_return_not_found_when_the_showcase_product_slug_makes_not_match_with_the_records_in_show_method(): void
    {
        $response = $this->getJson(route('api.showcase.show', 'abcd1234'));

        $response->assertNotFound();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'not found')
        );
    }
}
