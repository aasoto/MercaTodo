<?php

namespace Tests\Feature\Http\Controllers\Web\Client\Order;

use App\Domain\Order\Models\Order;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\Product\Models\Unit;
use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use Database\Seeders\OrderHasProductSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class OrderHasProductTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Order $order;

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

        $this->user = User::factory()->create([
            'email' => env('CLIENT_EMAIL'),
        ])->assignRole('client');

        Product::factory(100)->create();

        $this->order = Order::factory()->create();

        $this->seed(OrderHasProductSeeder::class);
    }

    public function test_can_list_products_of_an_order(): void
    {
        $response = $this->actingAs($this->user)
        ->get(route('order.show', $this->order->id));

        $response->assertStatus(200);

        $response->assertInertia(
            fn (Assert $page) => $page
                -> component('Order/Show')
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
