<?php

namespace Tests\Feature\Domain\Order\Model;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderHasProduct;
use App\Domain\Product\Models\Product;
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

class OrderHasProductsModelTest extends TestCase
{
    use RefreshDatabase;

    private Order $order;
    private Product $product_1, $product_2;
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

        $this->product_1 = Product::factory()->create();
        $this->product_2 = Product::factory()->create();

        $this->order = Order::factory()->create([
            'user_id' => $this->user->id,
            'purchase_total' => round($this->product_1->price + $this->product_2->price, 2),
        ]);

        OrderHasProduct::factory()->create([
            'order_id' => $this->order->id,
            'product_id' => $this->product_1->id,
            'quantity' => 1,
            'price' => $this->product_1->price,
        ]);

        OrderHasProduct::factory()->create([
            'order_id' => $this->order->id,
            'product_id' => $this->product_2->id,
            'quantity' => 1,
            'price' => $this->product_2->price,
        ]);
    }

    public function test_can_access_to_products_information(): void
    {
        $products = OrderHasProduct::where('order_id', $this->order->id)->get();

        $this->assertEquals($this->product_1->name, $products[0]->product->name);
        $this->assertEquals($this->product_2->name, $products[1]->product->name);
    }

    public function test_can_access_to_order_information(): void
    {
        $products = OrderHasProduct::where('order_id', $this->order->id)->get();

        $this->assertEquals($this->order->purchase_total, $products[0]->order->purchase_total);
    }
}
