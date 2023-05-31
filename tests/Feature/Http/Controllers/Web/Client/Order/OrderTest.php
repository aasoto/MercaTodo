<?php

namespace Tests\Feature\Http\Controllers\Web\Client\Order;

use App\Domain\Product\Models\Product;
use App\Domain\Order\Models\Order;
use App\Domain\User\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\OrderSeeder;
use Database\Seeders\OrderHasProductSeeder;
use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Database\Seeders\UnitSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class OrderTest extends TestCase
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

        $this->user = User::factory()->create([
            'email' => env('CLIENT_EMAIL'),
        ])->assignRole('client');

        $this->seed(OrderSeeder::class);
    }

    public function test_can_list_orders_by_client(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('order.index'));

        $response->assertStatus(200);

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('Order/Index')
                -> has('orders')
                -> has('orders.data.0', fn (Assert $page) => $page
                    -> has('id')
                    -> has('purchase_date')
                    -> has('payment_status')
                    -> etc()
                )
        );
    }

    public function test_can_create_new_order(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('order.create'));

        $response->assertStatus(200);

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('Order/Create')
                -> has('limitatedStock')
                -> has('success')
        );
    }

    public function test_can_save_new_order(): void
    {
        $products = Product::select('id', 'name', 'slug', 'price')->inRandomOrder()->take(5)->get();

        $order = array();
        $purchase_total = 0;
        foreach ($products as $key => $product) {
            array_push($order, [
                'id' => $product['id'],
                'name' => $product['name'],
                'slug' => $product['slug'],
                'price' => $product['price'],
                'quantity' => 1,
                'totalPrice' => $product['price'],
            ]);

            $purchase_total = $purchase_total + $product['price'];
        }

        $response = $this->actingAs($this->user)
            -> post(route('order.store'), [
                'products' => $order,
            ]);

        $response->assertRedirect(route('order.index'))
        ->assertSessionHasAll(['success' => 'Order created.']);

        $this->assertDatabaseHas('orders', [
            'purchase_date' => date('Y-m-d'),
            'purchase_total' => $purchase_total,
        ]);

        $order = Order::select('id')->orderByDesc('id')->first();

        foreach ($products as $key => $product) {
            $this->assertDatabaseHas('order_has_products', [
                'order_id' => $order['id'],
                'product_id' => $product['id'],
                'quantity' => 1,
                'price' => $product['price'],
            ]);
        }
    }

    public function test_can_not_save_order_if_the_quantity_of_a_product_is_insolvent(): void
    {
        $products = Product::select('id', 'name', 'slug', 'price', 'stock')->inRandomOrder()->get();

        $order = array();
        $rejected_data = array();
        $purchase_total = 0;
        foreach ($products as $key => $product) {
            array_push($order, [
                'id' => $product['id'],
                'name' => $product['name'],
                'slug' => $product['slug'],
                'price' => $product['price'],
                'quantity' => 11,
                'totalPrice' => $product['price'],
            ]);

            array_push($rejected_data, [
                'id' => $product['id'],
                'name' => $product['name'],
                'slug' => $product['slug'],
                'stock' => $product['stock'],
            ]);

            $purchase_total = $purchase_total + $product['price'];
        }

        $response = $this->actingAs($this->user)
            -> post(route('order.store'), [
                'products' => $order,
            ]);

        $response->assertRedirect(route('order.create'))
            ->assertSessionHasAll([
                'success' => 'Order rejected.',
                'limitatedStock' => json_encode($rejected_data),
            ]);
    }

    public function test_can_show_order_detail(): void
    {
        $this->seed(OrderHasProductSeeder::class);
        $order = Order::inRandomOrder()->first();

        $response = $this->actingAs($this->user)
        ->get(route('order.show', $order['id']));

        $response->assertStatus(200);

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('Order/Show')
                -> has('order', fn (Assert $page) => $page
                    -> where('id', $order['id'])
                    -> where('purchase_date', $order['purchase_date'])
                    -> where('payment_status', $order['payment_status'])
                    -> where('purchase_total', $order['purchase_total'])
                    -> etc()
                )
                -> has('products')
                -> has('products.data.0', fn (Assert $page) => $page
                    -> has('name')
                    -> has('price')
                    -> has('quantity')
                    -> has('unit')
                    -> etc()
                )
        );
    }
}
