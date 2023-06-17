<?php

namespace Tests\Feature\Domain\Product\Model;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderHasProduct;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\User\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Database\Seeders\UnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductModelTest extends TestCase
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
        ]);

        $this->user = User::factory()->create();
    }

    public function test_can_access_to_orders_of_product(): void
    {
        $order_1 = Order::factory()->create(['user_id' => $this->user->id]);
        $order_2 = Order::factory()->create(['user_id' => $this->user->id]);
        $order_3 = Order::factory()->create(['user_id' => $this->user->id]);
        $product = Product::factory()->create();

        OrderHasProduct::factory()->create([
            'order_id' => $order_1->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        OrderHasProduct::factory()->create([
            'order_id' => $order_2->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        OrderHasProduct::factory()->create([
            'order_id' => $order_3->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        $product = $product->fresh();

        $this->assertEquals(3, count($product->orders));
    }

    public function test_can_access_to_category_of_product(): void
    {
        $category = ProductCategory::factory()->create();
        $product = Product::factory()->create(['products_category_id' => $category->id]);

        $this->assertEquals($category->name, $product->category->name);
    }
}
