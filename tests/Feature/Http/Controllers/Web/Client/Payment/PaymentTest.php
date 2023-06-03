<?php

namespace Tests\Feature\Http\Controllers\Web\Client\Payment;

use App\Domain\Order\Models\Order;
use App\Domain\User\Models\User;
use Database\Seeders\CitySeeder;
use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StateSeeder;
use Database\Seeders\TypeDocumentSeeder;
use Database\Seeders\UnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentTest extends TestCase
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

        $this->user = User::factory()->create()->assignRole('client');
    }

    public function test_can_create_new_session(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);
        $mock_response = [
            'status' => [
                'status' => 'OK',
                'reason' => 'PC',
                'message' => 'La petición se ha procesado correctamente',
                'date' => '2023-06-02T16:09:39-05:00'
            ],
            'requestId' => 1213,
            'processUrl' => 'https://checkout-co.placetopay.dev/spa/session/1213/1234'
        ];

        Http::fake([config('placetopay.url').'/*' => Http::response($mock_response)]);

        $this->patchJson(route('payment.update', $order->getKey()), [
            'id' => $order->getKey(),
        ])->assertRedirectContains('order/'.$order->getKey());

        $this->assertEquals($order->url, '');
        $this->assertEquals($order->request_id, '');
        $order = $order->fresh();
        $this->assertEquals($order->url, 'https://checkout-co.placetopay.dev/spa/session/1213/1234');
        $this->assertEquals($order->request_id, '1213');
    }
}
