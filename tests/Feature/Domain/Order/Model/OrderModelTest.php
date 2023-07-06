<?php

namespace Tests\Feature\Domain\Order\Model;

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
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OrderModelTest extends TestCase
{
    use RefreshDatabase;

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

        $this->user = User::factory()->create();
    }

    public function test_can_change_payment_status_to_canceled(): void
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $this->assertEquals('pending', $order->payment_status);
        $order->canceled();
        $this->assertEquals('canceled', $order->payment_status);
    }

    public function test_can_change_payment_status_to_paid(): void
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $this->assertEquals('pending', $order->payment_status);
        $order->paid();
        $this->assertEquals('paid', $order->payment_status);
    }

    public function test_can_change_payment_status_to_pending(): void
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'payment_status' => 'canceled',
        ]);

        $this->assertEquals('canceled', $order->payment_status);
        $order->pending();
        $this->assertEquals('pending', $order->payment_status);
    }

    public function test_can_change_payment_status_to_waiting(): void
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $this->assertEquals('pending', $order->payment_status);
        $order->waiting();
        $this->assertEquals('waiting', $order->payment_status);
    }

    public function test_can_change_payment_status_to_verify_bank(): void
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $this->assertEquals('pending', $order->payment_status);
        $order->verify_bank();
        $this->assertEquals('verify_bank', $order->payment_status);
    }

    public function test_can_access_to_products_by_order(): void
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);
        $product = Product::factory()->create();

        OrderHasProduct::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        $order = $order->fresh();

        $this->assertEquals($product->id, $order->products[0]->product_id);
    }
}
