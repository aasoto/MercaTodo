<?php

namespace Tests\Feature\Http\Controllers\Web\Client\Showcase;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\User\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Database\Seeders\UnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ShowcaseTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->seed([
            StateSeeder::class,
            CitySeeder::class,
            RoleSeeder::class,
            TypeDocumentSeeder::class,
            ProductCategorySeeder::class,
            UnitSeeder::class,
            ProductSeeder::class,
        ]);

        $this->user = User::factory()->create()->assignRole('client');

    }

    public function test_can_show_showcase_of_products_without_role_specification(): void
    {

        $response = $this->actingAs($this->user)
        ->get(route('showcase.index'));

        $response->assertStatus(200);

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('Showcase/Index')
                -> has('products')
                -> has('products.data.0', fn (Assert $page) => $page
                    -> has('name')
                    -> has('slug')
                    -> has('category')
                    -> has('price')
                    -> has('unit')
                    -> has('picture_1')
                    -> etc()
                )
                -> has('userRole')
                -> has('filters')
        );
    }

    public function test_can_show_showcase_of_products_with_role_specification(): void
    {
        $response = $this->actingAs($this->user)->get(route('start'));

        $response->assertSessionHasAll(['user_role' => 'client']);
    }

    public function test_product_can_be_searched_by_name(): void
    {
        Product::factory()->create([
            'name' => 'televisor',
            'availability' => true,
        ]);

        $response = $this->actingAs($this->user)
        ->get('showcase?search=televisor');


        $response->assertStatus(200);

        $response->assertSee('televisor');
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
            ->get('showcase?category=electrodomesticos');

        $response->assertStatus(200);

        $response->assertSee('Licuadora oster');
    }

    public function test_can_search_product_by_range_of_prices(): void
    {
        Product::factory()->create([
            'name' => 'Plancha',
            'price' => '350000',
        ]);

        Product::factory()->create([
            'name' => 'Estufa a gas',
            'price' => '400000',
        ]);

        Product::factory()->create([
            'name' => 'Horno microndas',
            'price' => '450000',
        ]);

        Product::factory()->create([
            'name' => 'Estufa electrica',
            'price' => '500000',
        ]);

        $response = $this->actingAs($this->user)
            ->get('showcase?minPrice=400000&maxPrice=500000');

        $response->assertStatus(200);

        $response->assertDontSee('Plancha');
        $response->assertSee('Estufa a gas');
        $response->assertSee('Horno microndas');
        $response->assertSee('Estufa electrica');
    }

    public function test_show_description_of_product_from_the_showcase(): void
    {
        $product = Product::select('slug')->inRandomOrder()->first();

        $response = $this->actingAs($this->user)
        ->get(route('showcase.show', $product['slug']));

        $response->assertStatus(200);

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('Showcase/Show')
                -> has('product', fn (Assert $page) => $page
                    -> has('name')
                    -> has('slug')
                    -> has('category')
                    -> has('description')
                    -> has('price')
                    -> has('unit')
                    -> has('picture_1')
                    -> etc()
                )
        );
    }
}
