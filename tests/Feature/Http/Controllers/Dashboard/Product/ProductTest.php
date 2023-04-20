<?php

namespace Tests\Feature\Http\Controllers\Dashboard\Product;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use App\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Database\Seeders\UnitSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            StateSeeder::class,
            CitySeeder::class,
            RoleSeeder::class,
            TypeDocumentSeeder::class,
            UserSeeder::class,
            ProductCategorySeeder::class,
            UnitSeeder::class,
        ]);

        $this->product = Product::factory()->create();
        $this->user = User::factory()->create()->assignRole('admin');
    }

    public function test_can_show_page_of_products(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('products.index'));

        $response->assertStatus(200);

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('Product/Index')
                -> has('products')
                -> has('products.data.0', fn (Assert $page) => $page
                    -> has('name')
                    -> has('slug')
                    -> has('category')
                    -> has('price')
                    -> has('unit')
                    -> etc()
                )
                -> has('units')
        );
    }

    public function test_can_show_add_new_product_page(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('product.create'));

        $response->assertStatus(200);

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('Product/Create')
                -> has('products_categories')
                -> has('units')
        );
    }

    public function test_new_product_can_be_saved(): void
    {
        $category = ProductCategory::select('id')->inRandomOrder()->first();
        $unit = Unit::select('code')->inRandomOrder()->first();

        $response = $this->actingAs($this->user)
        -> post(route('product.store'), [
            'name' => fake()->words(4, true),
            'products_category_id' => $category['id'],
            'barcode' => fake()->randomNumber(5, true).fake()->randomNumber(5, true),
            'price' => fake()->randomFloat(2, 10000, 1000000),
            'unit' => $unit['code'],
            'stock' => fake()->randomNumber(2, true),
            'picture_1' => UploadedFile::fake()->image('fotoPrueba.png', 500, 500)->size(500),
        ]);

        $response->assertRedirect(route('products.index'));
    }

    public function test_can_show_product_information(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('product.show', $this->product->slug));

        $response->assertStatus(200);

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('Product/Show')
                -> has('product', fn (Assert $page) => $page
                    -> where('name', $this->product->name)
                    -> where('slug', $this->product->slug)
                    -> where('description', $this->product->description)
                    -> where('price', $this->product->price)
                    -> where('stock', $this->product->stock)
                    -> where('picture_1', $this->product->picture_1)
                    -> etc()
                )
        );
    }

    public function test_can_edit_product_information(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('product.edit', $this->product->slug));

        $response->assertStatus(200);

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('Product/Edit')
                -> has('product', fn (Assert $page) => $page
                    -> where('name', $this->product->name)
                    -> where('description', $this->product->description)
                    -> where('price', $this->product->price)
                    -> where('stock', $this->product->stock)
                    -> where('picture_1', $this->product->picture_1)
                    -> etc()
                )
                -> has('products_categories')
                -> has('units')
        );
    }

    public function test_can_update_product_information(): void
    {
        $response = $this->actingAs($this->user)
        ->patch(route('product.update', $this->product->slug));

        $response->assertStatus(200);

        $response->assertInertia(fn (Assert $page) => $page
                ->component('Product/Edit')
                ->has('status')
                ->etc()
        );
    }

    // public function test_can_destroy_product(): void
    // {
    //     $response = $this->actingAs($this->user)
    //     ->delete('product/'.$this->product->slug);

    //     $response->assertStatus(200);

    //     $response->assertInertia(fn (Assert $page) => $page
    //             ->component('Product/Index')
    //             ->has('status')
    //             ->etc()
    //     );
    // }
}
