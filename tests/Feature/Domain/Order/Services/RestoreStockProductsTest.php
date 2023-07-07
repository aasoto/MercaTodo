<?php

namespace Tests\Feature\Domain\Order\Services;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderHasProduct;
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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RestoreStockProductsTest extends TestCase
{
    use RefreshDatabase;

    private Order $order;
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

        $this->user = User::factory()->create()->assignRole('client');

        $this->order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
            'url' => 'https://checkout-co.placetopay.dev/spa/session/1213/1234',
            'request_id' => 1213,
            'payment_status' => 'canceled'
        ]);
    }

    public function test_can_regenerate_payment_link_in_canceled_solvent_order(): void
    {
        $product_1 = Product::factory()->create();
        $product_2 = Product::factory()->create();

        OrderHasProduct::factory()->create([
            'order_id' => $this->order->id,
            'product_id' => $product_1->id,
            'quantity' => 1,
            'price' => $product_1->price,
        ]);

        OrderHasProduct::factory()->create([
            'order_id' => $this->order->id,
            'product_id' => $product_2->id,
            'quantity' => 1,
            'price' => $product_2->price,
        ]);

        $mock_response = [
            "status" => [
                "status" => "OK",
                "reason" => "PC",
                "message" => "La petición se ha procesado correctamente",
                "date" => "2023-06-04T15:07:52-05:00"
            ],
            "requestId" => 72994,
            "processUrl" => "https://checkout-co.placetopay.dev/spa/session/72994/1234",
        ];

        Http::fake([config('placetopay.url').'/*' => Http::response($mock_response)]);

        $this->actingAs($this->user)->patchJson(route('payment.update', $this->order->getKey()), [
            'id' => $this->order->getKey(),
            'payment_method' => 'NORMAL',
        ])->assertRedirectContains('order/'.$this->order->getKey());

        $this->assertEquals($this->order->url, 'https://checkout-co.placetopay.dev/spa/session/1213/1234');
        $this->assertEquals($this->order->request_id, '1213');
        $this->assertEquals($this->order->payment_status, 'canceled');

        $this->order = $this->order->fresh();

        $this->assertEquals($this->order->url, 'https://checkout-co.placetopay.dev/spa/session/72994/1234');
        $this->assertEquals($this->order->request_id, '72994');
        $this->assertEquals($this->order->payment_status, 'pending');
    }

    public function test_can_redirect_to_create_order_when_the_order_is_insolvent(): void
    {
        $product_1 = Product::factory()->create(['stock' => 0]);
        $product_2 = Product::factory()->create();

        OrderHasProduct::factory()->create([
            'order_id' => $this->order->id,
            'product_id' => $product_1->id,
            'quantity' => 1,
            'price' => $product_1->price,
        ]);

        OrderHasProduct::factory()->create([
            'order_id' => $this->order->id,
            'product_id' => $product_2->id,
            'quantity' => 1,
            'price' => $product_2->price,
        ]);

        $limitated_stock = array();
        array_push($limitated_stock, [
            'id' => $product_1->id,
            'name' => $product_1->name,
            'slug' => $product_1->slug,
            'stock' => $product_1->stock,
        ]);

        $this->actingAs($this->user)->patchJson(route('payment.update', $this->order->getKey()), [
            'id' => $this->order->getKey(),
            'payment_method' => 'NORMAL',
        ])->assertRedirectContains(route('order.create'))
            ->assertSessionHasAll([
                'success' => 'Order rejected.',
                'cancel' => 'Restore order.',
                'limitatedStock' => json_encode($limitated_stock),
                'orderId' => $this->order->id,
            ]);
    }

    public function test_can_regenerate_payment_link_in_canceled_resolvent_order(): void
    {
        $product_1 = Product::factory()->create(['stock' => 0]);
        $product_2 = Product::factory()->create();

        OrderHasProduct::factory()->create([
            'order_id' => $this->order->id,
            'product_id' => $product_1->id,
            'quantity' => 1,
            'price' => $product_1->price,
        ]);

        OrderHasProduct::factory()->create([
            'order_id' => $this->order->id,
            'product_id' => $product_2->id,
            'quantity' => 1,
            'price' => $product_2->price,
        ]);

        $mock_response = [
            "status" => [
                "status" => "OK",
                "reason" => "PC",
                "message" => "La petición se ha procesado correctamente",
                "date" => "2023-06-04T15:07:52-05:00"
            ],
            "requestId" => 72994,
            "processUrl" => "https://checkout-co.placetopay.dev/spa/session/72994/1234",
        ];

        $cart = array([
            'id' => $product_2->id,
            'name' => $product_2->name,
            'slug' => $product_2->slug,
            'price'=> $product_2->price,
            'quantity'=> 1,
            'totalPrice'=> $product_2->price,
        ]);

        Http::fake([config('placetopay.url').'/*' => Http::response($mock_response)]);

        $this->actingAs($this->user)->patchJson(route('payment.update', $this->order->getKey()), [
            'id' => $this->order->getKey(),
            'payment_method' => 'NORMAL',
            'products' => $cart,
        ])->assertRedirectContains('order/'.$this->order->getKey());

        $this->assertEquals($this->order->url, 'https://checkout-co.placetopay.dev/spa/session/1213/1234');
        $this->assertEquals($this->order->request_id, '1213');
        $this->assertEquals($this->order->payment_status, 'canceled');

        $this->order = $this->order->fresh();

        $this->assertEquals($this->order->url, 'https://checkout-co.placetopay.dev/spa/session/72994/1234');
        $this->assertEquals($this->order->request_id, '72994');
        $this->assertEquals($this->order->payment_status, 'pending');
    }

    public function test_can_cancel_payment_link_and_report_the_status_as_canceled(): void
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
            'url' => 'https://checkout-co.placetopay.dev/spa/session/1213/1234',
            'request_id' => 1213,
            'payment_status' => 'pending'
        ]);

        $product_1 = Product::factory()->create(['stock' => 8]);
        $product_2 = Product::factory()->create(['stock' => 8]);

        OrderHasProduct::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product_1->id,
            'quantity' => 1,
            'price' => $product_1->price,
        ]);

        OrderHasProduct::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product_2->id,
            'quantity' => 1,
            'price' => $product_2->price,
        ]);

        $mock_response = [
            "requestId" => 72994,
            "status" => [
                "status" => "REJECTED",
                "reason" => "?C",
                "message" => "La petición ha sido cancelada por el usuario",
                "date" => "2023-06-04T15:06:14-05:00"
            ]
        ];

        Http::fake([config('placetopay.url').'/*' => Http::response($mock_response)]);

        $this->actingAs($this->user)->getJson(route('payment.canceled', $order->code))
            ->assertRedirectContains(route('showcase.index'))
            ->assertSessionHasAll([
                'success' => 'Payment canceled.',
            ]);

        $this->assertEquals($order->payment_status, 'pending');
        $order = $order->fresh();
        $this->assertEquals($order->payment_status, 'canceled');

        $this->assertEquals($product_1->stock, 8);
        $product_1 = $product_1->fresh();
        $this->assertEquals($product_1->stock, 9);

        $this->assertEquals($product_2->stock, 8);
        $product_2 = $product_2->fresh();
        $this->assertEquals($product_2->stock, 9);

    }
}
