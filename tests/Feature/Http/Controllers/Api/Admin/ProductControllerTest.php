<?php

namespace Tests\Feature\Http\Controllers\Api\Admin;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\Product\Models\Unit;
use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->seed([
            RoleSeeder::class,
        ]);

        ProductCategory::factory()->create();
        Unit::factory()->create();

        $this->product = Product::factory()->create([
            'price' => 89999.42
        ]);

        Product::factory()->count(3)->create();

        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();

        $user = User::factory()->create()->assignRole('admin');

        Sanctum::actingAs($user, ['admin']);
    }

    public function test_can_list_products_from_api(): void
    {
        $response = $this->getJson(route('api.product.index'));

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

    public function test_can_save_new_product_from_api(): void
    {
        /**
         * @var Product $new_product
         */
        $new_product = Product::factory()->make();

        $response = $this->postJson(route('api.product.store'), [
            'name' => $new_product->name,
            'products_category_id' => $new_product->products_category_id,
            'barcode' => $new_product->barcode,
            'description' => $new_product->description,
            'price' => $new_product->price,
            'unit' => $new_product->unit,
            'stock' => $new_product->stock,
            'picture_1' => UploadedFile::fake()->image('fotoPrueba.png', 500, 500)->size(500),
            'picture_2' => UploadedFile::fake()->image('fotoPrueba.png', 500, 500)->size(500),
            'picture_3' => UploadedFile::fake()->image('fotoPrueba.png', 500, 500)->size(500),
        ]);

        $response->assertStatus(201);
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'message.created')
            ->has('data', fn (AssertableJson $json) =>
                $json->where('name', $new_product->name)
                    ->where('barcode', $new_product->barcode)
                    ->where('description', $new_product->description)
                    ->where('price', strval($new_product->price))
                    ->etc()
            )
        );
    }

    public function test_can_show_product_from_api(): void
    {
        $response = $this->getJson(route('api.product.show', $this->product->slug));

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

    public function test_can_return_not_found_when_the_slug_makes_not_match_with_the_records_in_show_method(): void
    {
        $response = $this->getJson(route('api.product.show', 'abcd1234'));

        $response->assertNotFound();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'not found')
        );
    }

    public function test_can_update_product_from_api(): void
    {
        /**
         * @var Product $product
         */
        $product = Product::first();

        $response = $this->patchJson(route('api.product.update', $product->id), [
            'name' => 'Product edited from api test',
            'products_category_id' => $product->products_category_id,
            'barcode' => $product->barcode,
            'description' => $product->description,
            'price' => 50000,
            'unit' => $product->unit,
            'stock' => $product->stock,
            'picture_1' => UploadedFile::fake()->image('fotoPrueba.png', 500, 500)->size(500),
            'picture_2' => '',
            'picture_3' => '',
            'availability' => $product->availability,
        ]);

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'message.updated')
                ->has('data', fn (AssertableJson $json) =>
                $json->where('name', 'Product edited from api test')
                    ->where('barcode', $product->barcode)
                    ->where('description', $product->description)
                    ->where('price', 50000)
                    ->whereNot('picture_1', '/storage/images/products/'.$product->picture_1)
                    ->etc()
            )
        );

        Storage::disk('public')->assertMissing('images/products/'.$product->picture_1);
        $product = $product->fresh();
        Storage::disk('public')->assertExists('images/products/'.$product->picture_1);
    }

    public function test_can_return_not_found_when_the_id_makes_not_match_with_the_records_in_update_method(): void
    {
        /**
         * @var Product $product
         */
        $product = Product::first();

        $response = $this->patchJson(route('api.product.update', 12345678), [
            'name' => 'Product edited from api test2',
            'products_category_id' => $product->products_category_id,
            'barcode' => '12121212',
            'description' => $product->description,
            'price' => 50000,
            'unit' => $product->unit,
            'stock' => $product->stock,
            'picture_1' => UploadedFile::fake()->image('fotoPrueba.png', 500, 500)->size(500),
            'picture_2' => '',
            'picture_3' => '',
            'availability' => $product->availability,
        ]);

        $response->assertNotFound();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'not found')
        );
    }

    public function test_can_delete_product_from_api(): void
    {
        /**
         * @var Product $product
         */
        $product = Product::first();

        $response = $this->deleteJson(route('api.product.destroy', $product->slug));

        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'message.deleted')
        );

        Storage::disk('public')->assertMissing([
            'images/products/'.$product->picture_1,
            'images/products/'.$product->picture_2,
            'images/products/'.$product->picture_3,
        ]);
    }

    public function test_can_show_not_found_message_on_delete_action(): void
    {
        $response = $this->deleteJson(route('api.product.destroy', 'abc'));

        $response->assertNotFound();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'not found')
        );
    }
}
