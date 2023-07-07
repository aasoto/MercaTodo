<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Product;

use App\Domain\Product\Models\ProductCategory;
use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ProductCategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private ProductCategory $product_category;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([
            RoleSeeder::class,
        ]);

        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();
        ProductCategory::factory()->create();

        $this->user = User::factory()->create()->assignRole('admin');
        $this->product_category = ProductCategory::factory()->create();
    }

    public function test_can_list_products_categories(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('product_category.index'));

        $response->assertOk();

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('Product/Category/Index')
                -> has('categories')
                -> has('categories.0', fn (Assert $page) => $page
                    -> has('id')
                    -> has('name')
                )
        );
    }

    public function test_can_show_form_for_create_new_product(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('product_category.create'));

        $response->assertOk();

        $response->assertInertia( fn (Assert $page) => $page -> component('Product/Category/Create'));
    }

    public function test_can_save_new_product_category(): void
    {
        $name = fake()->words(3, true);
        $response = $this->actingAs($this->user)
        ->post(route('product_category.store'), [
            'name' => $name,
        ]);

        $this->assertDatabaseHas('products_categories', ['name' => $name]);

        $response->assertRedirect(route('product_category.index'))
        ->assertSessionHasAll(['success' => 'Product category created.']);
    }

    public function test_can_show_form_for_edit_product(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('product_category.edit', $this->product_category->id));

        $response->assertOk();

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('Product/Category/Edit')
                -> has('category', fn (Assert $page) => $page
                    -> where('name', $this->product_category->name)
                    -> etc()
                )
        );
    }

    public function test_can_update_unit(): void
    {
        $name = fake()->words(3, true);

        $response = $this->actingAs($this->user)
        ->patch(route('product_category.update', $this->product_category->id), [
            'name' => $name,
        ]);

        $response->assertFound();

        $this->product_category->refresh();
        $this->assertSame($name, $this->product_category->name);
        $this->assertDatabaseHas('products_categories', [
            'name' => $name,
        ]);

        $response->assertRedirect(route('product_category.index'))
        ->assertSessionHasAll(['success' => 'Product category updated.']);
    }
}
