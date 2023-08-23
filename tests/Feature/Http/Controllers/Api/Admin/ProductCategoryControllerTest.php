<?php

namespace Tests\Feature\Http\Controllers\Api\Admin;

use App\Domain\Product\Models\ProductCategory;
use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            RoleSeeder::class,
        ]);
        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();

        $user = User::factory()->create()->assignRole('admin');

        Sanctum::actingAs($user, ['admin']);
    }

    public function test_can_list_products_categories_from_api(): void
    {
        /**
         * @var ProductCategory $product_category
         */
        $product_category = ProductCategory::factory()->create();

        $response = $this->getJson(route('api.product.category.index'));

        $response->assertOk()
        ->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 1)
            ->has('data.0', fn (AssertableJson $json) =>
                $json->where('id', $product_category->id)
                ->where('name', $product_category->name)
            )
        );
    }

    public function test_can_save_product_category_from_api(): void
    {
        /**
         * @var ProductCategory $product_category
         */
        $product_category = ProductCategory::factory()->make();

        $data = [
            'name' => $product_category->name,
        ];

        $response = $this->postJson(route('api.product.category.store'), $data);

        $response->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'message.created')
            ->has('data', fn (AssertableJson $json) =>
                $json->where('name', $data['name'])
                    ->etc()
            )
        );
    }

    public function test_can_show_product_category_from_api(): void
    {
        /**
         * @var ProductCategory $product_category
         */
        $product_category = ProductCategory::factory()->create();

        $response = $this->getJson(route('api.product.category.show', $product_category->id));

        $response->assertOk()
        ->assertJson(fn (AssertableJson $json) =>
            $json->has('data', fn (AssertableJson $json) =>
                $json->where('id', $product_category->id)
                    ->where('name', $product_category->name)
            )
        );
    }

    public function test_can_return_not_found_when_the_category_id_makes_not_match_with_the_records_in_show_method(): void
    {
        $response = $this->getJson(route('api.product.category.show', 12345));

        $response->assertNotFound();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'not found')
        );
    }

    public function test_can_update_product_category_from_api(): void
    {
        /**
         * @var ProductCategory $unit
         */
        $product_category = ProductCategory::factory()->create();

        $response = $this->patchJson(route('api.product.category.update', $product_category->id), [
            'name' => 'change category',
        ]);

        $response->assertOk()
        ->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'message.updated')
                ->has('data', fn (AssertableJson $json) =>
                $json->where('id', $product_category->id)
                    ->where('name', 'change category')
            )
        );
    }

    public function test_can_return_not_found_updating_product_category_from_api_when_id_does_not_match_with_records(): void
    {
        $response = $this->patchJson(route('api.product.category.update', 12345678), [
            'name' => 'change category2',
        ]);

        $response->assertNotFound();
        $response->assertJson(fn (AssertableJson $json) =>
            $json->where('message', 'not found')
        );
    }
}
