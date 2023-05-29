<?php

namespace Tests\Feature\Http\Controllers\Web\Client\Order;

use App\Domain\Order\Models\Order;
use App\Domain\Product\Models\Product;
use App\Domain\User\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\OrderHasProductSeeder;
use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Database\Seeders\UnitSeeder;
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
            StateSeeder::class,
            CitySeeder::class,
            RoleSeeder::class,
            TypeDocumentSeeder::class,
            ProductCategorySeeder::class,
            UnitSeeder::class,
        ]);

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
                -> has('products.0', fn (Assert $page) => $page
                    -> has('name')
                    -> has('price')
                    -> has('quantity')
                    -> has('unit')
                    -> etc()
                )
        );
    }
}
