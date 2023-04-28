<?php

namespace Tests\Feature\Http\Controllers\Client\Showcase;

use App\Models\Product;
use App\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Database\Seeders\UnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\Feature\Traits\refreshStorage;
use Tests\TestCase;

class ShowcaseTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

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

        // $this->cleanProductsImages();
    }

    public function test_can_show_showcase_of_products(): void
    {
        Storage::fake();

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

    public function test_show_description_of_product_from_the_showcase(): void
    {
        Storage::fake();
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
