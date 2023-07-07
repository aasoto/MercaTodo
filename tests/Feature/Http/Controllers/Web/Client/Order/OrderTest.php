<?php

namespace Tests\Feature\Http\Controllers\Web\Client\Order;

use App\Domain\Product\Models\Product;
use App\Domain\Order\Models\Order;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\Product\Models\Unit;
use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\OrderFullSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
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

        State::factory()->create();
        City::factory()->create();
        TypeDocument::factory()->create();
        ProductCategory::factory()->create();
        Unit::factory()->create();
        Product::factory()->count(7)->create();

        $this->seed([
            RoleSeeder::class,
        ]);

        $this->user = User::factory()->create([
            'email' => env('CLIENT_EMAIL'),
        ])->assignRole('client');

        $this->seed(OrderFullSeeder::class);
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

    public function test_can_save_new_order_with_payment_method_normal(): void
    {
        $products = Product::select('id', 'name', 'slug', 'price')
            ->inRandomOrder()
            ->take(5)
            ->get();

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

        $request_id = 1213;
        $url = "https://checkout-co.placetopay.dev/spa/session/1213/1234";
        $mock_response = [
            "status" => [
                "status" => "OK",
                "reason" => "PC",
                "message" => "La petición se ha procesado correctamente",
                "date" => "2023-06-02T16:09:39-05:00"
            ],
            "requestId" => $request_id,
            "processUrl" => $url
        ];

        Http::fake([config('placetopay.url').'/*' => Http::response($mock_response)]);

        $response = $this->actingAs($this->user)
            -> post(route('order.store'), [
                'products' => $order,
                'payment_method' => 'NORMAL'
            ]);

        $order = Order::select('id')->orderByDesc('id')->first();

        $response->assertRedirect(route('order.show', $order->id))
        ->assertSessionHasAll(['success' => 'Order created.']);

        $this->assertDatabaseHas('orders', [
            'purchase_total' => round($purchase_total, 2),
            'request_id' => strval($request_id),
            'url' => $url,
        ]);

        foreach ($products as $key => $product) {
            $this->assertDatabaseHas('order_has_products', [
                'order_id' => $order['id'],
                'product_id' => $product['id'],
                'quantity' => 1,
                'price' => $product['price'],
            ]);
        }
    }

    public function test_can_save_new_order_with_payment_method_allow_partial(): void
    {
        $products = Product::select('id', 'name', 'slug', 'price')
            ->inRandomOrder()
            ->take(5)
            ->get();

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

        $request_id = 1213;
        $url = "https://checkout-co.placetopay.dev/spa/session/1213/1234";
        $mock_response = [
            "status" => [
                "status" => "OK",
                "reason" => "PC",
                "message" => "La petición se ha procesado correctamente",
                "date" => "2023-06-02T16:09:39-05:00"
            ],
            "requestId" => $request_id,
            "processUrl" => $url
        ];

        Http::fake([config('placetopay.url').'/*' => Http::response($mock_response)]);

        $response = $this->actingAs($this->user)
            -> post(route('order.store'), [
                'products' => $order,
                'payment_method' => 'ALLOW_PARTIAL'
            ]);

        $order = Order::select('id')->orderByDesc('id')->first();

        $response->assertRedirect(route('order.show', $order->id))
        ->assertSessionHasAll(['success' => 'Order created.']);

        $this->assertDatabaseHas('orders', [
            'purchase_total' => round($purchase_total, 2),
            'request_id' => strval($request_id),
            'url' => $url,
        ]);

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
                'payment_method' => 'NORMAL'
            ]);

        $response->assertRedirect(route('order.create'))
            ->assertSessionHasAll([
                'success' => 'Order rejected.',
                'limitatedStock' => json_encode($rejected_data),
            ]);
    }

    public function test_can_show_order_detail(): void
    {
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
