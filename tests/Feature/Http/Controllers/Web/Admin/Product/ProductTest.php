<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Product;

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

        Storage::fake('public');

        $this->seed([
            RoleSeeder::class,
        ]);

        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();
        ProductCategory::factory()->create();
        Unit::factory()->create();

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
                -> has('success')
        );
    }

    public function test_can_search_product_by_name(): void
    {
        Product::factory()->create([
            'name' => 'Camara'
        ]);

        $response = $this->actingAs($this->user)
            ->get('products?search=Camara');

        $response->assertStatus(200);

        $response->assertSee('Camara');
    }

    public function test_can_search_product_by_category(): void
    {
        $category = ProductCategory::factory()->create([
            'name' => 'electrodomesticos',
        ]);

        Product::factory()->create([
            'name' => 'Licuadora oster',
            'products_category_id' => $category['id'],
        ]);

        $response = $this->actingAs($this->user)
            ->get('products?category=electrodomesticos');

        $response->assertStatus(200);

        $response->assertSee('Licuadora oster');
    }

    public function test_can_search_enabled_products(): void
    {
        Product::factory()->create([
            'name' => 'Licuadora oster',
            'availability' => true,
        ]);

        $response = $this->actingAs($this->user)
            ->get('products?availability=true');

        $response->assertStatus(200);

        $response->assertSee('Licuadora oster');
    }

    public function test_can_search_disabled_products(): void
    {
        Product::factory()->create([
            'name' => 'Licuadora oster',
            'availability' => false,
        ]);

        $response = $this->actingAs($this->user)
            ->get('products?availability=false');

        $response->assertStatus(200);

        $response->assertSee('Licuadora oster');
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
        $name = fake()->words(4, true);

        $response = $this->actingAs($this->user)
        -> post(route('product.store'), [
            'name' => $name,
            'products_category_id' => $category['id'],
            'barcode' => fake()->randomNumber(5, true).fake()->randomNumber(5, true),
            'price' => fake()->randomFloat(2, 10000, 1000000),
            'unit' => $unit['code'],
            'stock' => fake()->randomNumber(2, true),
            'picture_1' => UploadedFile::fake()->image('fotoPrueba.png', 500, 500)->size(500),
            'picture_2' => UploadedFile::fake()->image('fotoPrueba.png', 500, 500)->size(500),
            'picture_3' => UploadedFile::fake()->image('fotoPrueba.png', 500, 500)->size(500),
        ]);

        $this->assertDatabaseHas('products', [
            'name' => $name,
        ]);

        $response->assertRedirect(route('products.index'))
        ->assertSessionHasAll(['success' => 'Product created.']);
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
        $files = array(
            'picture_1' => $this->product->picture_1,
            'picture_2' => $this->product->picture_2,
            'picture_3' => $this->product->picture_3
        );

        $name = fake()->words(4, true);

        $response = $this->actingAs($this->user)
        ->patch('product/edit/'.$this->product->id.'/'.json_encode($files), [
            'name' => $name,
            'products_category_id' => $this->product->products_category_id,
            'barcode' => $this->product->barcode,
            'description' => $this->product->description,
            'price' => $this->product->price,
            'unit' => $this->product->unit,
            'stock' => $this->product->stock,
            'availability' => true,
        ]);

        $response->assertFound();

        $this->product->refresh();
        $this->assertSame($name, $this->product->name);
        $this->assertDatabaseHas('products', [
            'name' => $name,
        ]);

        $response->assertRedirect(route('product.edit', $this->product->slug))
        ->assertSessionHasAll(['success' => 'Product updated.']);

    }

    public function test_can_destroy_product(): void
    {
        $name = $this->product->name;

        $response = $this->actingAs($this->user)
        ->delete(route('product.destroy', $this->product->slug));

        $this->assertDatabaseMissing('products', [
            'name' => $name,
        ]);

        $response->assertFound();
        $response->assertRedirect(route('products.index'))
        ->assertSessionHasAll(['success' => 'Product deleted.']);

    }
}
