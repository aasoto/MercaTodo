<?php

namespace Tests\Feature\Console;

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

class ConsoleTest extends TestCase
{
    use RefreshDatabase;

    private Order $order;
    private User $user;
    private array $mock_response;

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

        $this->order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
            'url' => 'https://checkout-co.placetopay.dev/spa/session/0000/0000',
            'request_id' => 0000,
            'payment_status' => 'pending',
        ]);

        $this->mock_response = [
            "requestId" => 0000,
            "status" => [
                "status" => "APPROVED",
                "reason" => "00",
                "message" => "La petición ha sido aprobada exitosamente",
                "date" => "2023-06-04T12:04:01-05:00"
            ]
        ];
    }

    public function test_can_execute_commands_in_schedule_method(): void
    {
        Http::fake([config('placetopay.url').'/*' => Http::response($this->mock_response)]);

        $this->artisan('schedule:run')->assertExitCode(0);
    }

    public function test_can_execute_command_consult_session(): void
    {

        Http::fake([config('placetopay.url').'/*' => Http::response($this->mock_response)]);

        $this->artisan('app:consult-session')->assertExitCode(0);

        $this->assertEquals($this->order->payment_status, 'pending');

        $this->order = $this->order->fresh();

        $this->assertEquals($this->order->payment_status, 'paid');
    }
}
