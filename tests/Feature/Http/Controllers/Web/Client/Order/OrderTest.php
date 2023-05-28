<?php

namespace Tests\Feature\Http\Controllers\Web\Client\Order;

use App\Domain\User\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\OrderSeeder;
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
}
