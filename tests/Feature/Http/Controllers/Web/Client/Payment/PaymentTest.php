<?php

namespace Tests\Feature\Http\Controllers\Web\Client\Payment;

use App\Domain\Order\Models\Order;
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

class PaymentTest extends TestCase
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
        Product::factory()->count(3)->create();

        $this->user = User::factory()->create()->assignRole('client');
    }

    public function test_can_redirect_to_placetopay_webcheckout(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
            'url' => 'https://checkout-co.placetopay.dev/spa/session/1213/1234',
            'request_id' => 1213,
        ]);

        $response = $this->get(route('payment.show', $order->code));

        $response->assertRedirect()
            ->assertRedirectContains($order->url);
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
            'payment_method' => 'NORMAL',
        ])->assertRedirectContains('order/'.$order->getKey());

        $this->assertEquals($order->url, '');
        $this->assertEquals($order->request_id, '');

        $order = $order->fresh();

        $this->assertEquals($order->url, 'https://checkout-co.placetopay.dev/spa/session/1213/1234');
        $this->assertEquals($order->request_id, '1213');
    }

    public function test_can_regenerate_new_payment_link_in_canceled_order(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
            'url' => 'https://checkout-co.placetopay.dev/spa/session/0000/0000',
            'request_id' => 0000,
            'payment_status' => 'canceled',
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

        $product = Product::inRandomOrder()->first();

        $this->patchJson(route('payment.update', $order->getKey()), [
            'id' => $order->getKey(),
            'payment_method' => 'NORMAL',
            'products' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price'=> $product->price,
                'quantity'=> 1,
                'totalPrice'=> $product->price,
            ]
        ])->assertRedirectContains('order/'.$order->getKey());

        $this->assertEquals($order->url, 'https://checkout-co.placetopay.dev/spa/session/0000/0000');
        $this->assertEquals($order->request_id, '0000');
        $this->assertEquals($order->payment_status, 'canceled');

        $order = $order->fresh();

        $this->assertEquals($order->url, 'https://checkout-co.placetopay.dev/spa/session/72994/1234');
        $this->assertEquals($order->request_id, '72994');
        $this->assertEquals($order->payment_status, 'pending');
    }

    public function test_can_pay_order_from_the_webcheckout_platform(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
            'url' => 'https://checkout-co.placetopay.dev/spa/session/0000/0000',
            'request_id' => 0000,
        ]);

        $mock_response = [
            "requestId" => 0000,
            "status" => [
                "status" => "APPROVED",
                "reason" => "00",
                "message" => "La petición ha sido aprobada exitosamente",
                "date" => "2023-06-04T12:04:01-05:00"
            ],
            'payment' => [
                0 => [
                    'reference' => $order->code,
                    'amount' => [
                        'from' => [
                            'total' => $order->purchase_total,
                        ]
                    ]
                ]
            ]
        ];

        Http::fake([config('placetopay.url').'/*' => Http::response($mock_response)]);

        $this->getJson(route('payment.response', $order->code))
            ->assertRedirectContains('order/'.$order->getKey());

        $this->assertEquals($order->payment_status, 'pending');

        $order = $order->fresh();

        $this->assertEquals($order->payment_status, 'paid');
    }

    public function test_can_cancel_payment_process_from_webcheckout_platform(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
            'url' => 'https://checkout-co.placetopay.dev/spa/session/0000/0000',
            'request_id' => 0000,
        ]);

        $mock_response = [
            "requestId" => 0000,
            "status" => [
                "status" => "REJECTED",
                "reason" => "?C",
                "message" => "La petición ha sido cancelada por el usuario",
                "date" => "2023-06-04T15:06:14-05:00"
            ]
        ];

        Http::fake([config('placetopay.url').'/*' => Http::response($mock_response)]);

        $this->getJson(route('payment.response', $order->code))
            ->assertRedirectContains('order/'.$order->getKey());

        $this->assertEquals($order->payment_status, 'pending');

        $order = $order->fresh();

        $this->assertEquals($order->payment_status, 'canceled');
    }

    public function test_can_report_transaction_in_process(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
            'url' => 'https://checkout-co.placetopay.dev/spa/session/0000/0000',
            'request_id' => 0000,
        ]);

        $mock_response = [
            "requestId" => 0000,
            "status" => [
                "status" => "PENDING",
                "reason" => "PE",
                "message" => "La petición se encuentra pendiente",
                "date" => "2023-06-04T14:57:16-05:00"
            ],
            "payment" => [
                0 => [
                    "status" => [
                        "status" => "PENDING",
                        "message" => "Transacción pendiente de validación",
                    ]
                ]
            ]
        ];

        Http::fake([config('placetopay.url').'/*' => Http::response($mock_response)]);

        $this->getJson(route('payment.response', $order->code))
            ->assertRedirectContains('order/'.$order->getKey());

        $this->assertEquals($order->payment_status, 'pending');

        $order = $order->fresh();

        $this->assertEquals($order->payment_status, 'waiting');
    }

    public function test_can_verify_if_the_session_is_stil_active(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
            'url' => 'https://checkout-co.placetopay.dev/spa/session/0000/0000',
            'request_id' => 0000,
        ]);

        $mock_response = [
            "requestId" => 0000,
            "status" => [
                "status" => "PENDING",
                "reason" => "PC",
                "message" => "La petición se encuentra activa",
                "date" => "2023-06-04T14:58:07-05:00"
            ]
        ];

        Http::fake([config('placetopay.url').'/*' => Http::response($mock_response)]);

        $this->getJson(route('payment.response', $order->code))
            ->assertRedirectContains('order/'.$order->getKey());

        $this->assertEquals($order->payment_status, 'pending');

        $order = $order->fresh();

        $this->assertEquals($order->payment_status, 'pending');
    }

    public function test_can_report_rejected_transaction_in_pending_payment(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
            'url' => 'https://checkout-co.placetopay.dev/spa/session/0000/0000',
            'request_id' => 0000,
        ]);

        $mock_response = [
            "requestId" => 0000,
            "status" => [
                "status" => "PENDING",
                "reason" => "PE",
                "message" => "La petición se encuentra pendiente",
                "date" => "2023-06-04T14:57:16-05:00"
            ],
            "payment" => [
                0 => [
                    "status" => [
                        "status" => "REJECTED",
                        "message" => "Transacción pendiente. Por favor consulte con su entidad financiera si el débito fue realizado",
                    ]
                ]
            ]
        ];

        Http::fake([config('placetopay.url').'/*' => Http::response($mock_response)]);

        $this->getJson(route('payment.response', $order->code))
            ->assertRedirectContains('order/'.$order->getKey());

        $this->assertEquals($order->payment_status, 'pending');

        $order = $order->fresh();

        $this->assertEquals($order->payment_status, 'verify_bank');
    }

    public function test_can_change_payment_status_when_the_webcheckout_link_has_expired(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
            'url' => 'https://checkout-co.placetopay.dev/spa/session/0000/0000',
            'request_id' => 0000,
        ]);

        $mock_response = [
            "requestId" => 0000,
            "status" => [
                "status" => "REJECTED",
                "reason" => "EX",
                "message" => "La petición ha expirado",
                "date" => "2023-06-04T11:48:41-05:00"
            ]
        ];

        Http::fake([config('placetopay.url').'/*' => Http::response($mock_response)]);

        $this->getJson(route('payment.response', $order->code))
            ->assertRedirectContains('order/'.$order->getKey());

        $this->assertEquals($order->payment_status, 'pending');

        $order = $order->fresh();

        $this->assertEquals($order->payment_status, 'canceled');
    }

    public function test_can_change_payment_status_when_client_has_been_redirect_from_canceled_link(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
            'url' => 'https://checkout-co.placetopay.dev/spa/session/0000/0000',
            'request_id' => 0000,
        ]);

        $mock_response = [
            "requestId" => 0000,
            "status" => [
                "status" => "REJECTED",
                "reason" => "?C",
                "message" => "La petición ha sido cancelada por el usuario",
                "date" => "2023-06-04T22:01:16-05:00"
            ]
        ];

        Http::fake([config('placetopay.url').'/*' => Http::response($mock_response)]);

        $this->getJson(route('payment.canceled', $order->code))
            ->assertRedirect(route('showcase.index'))
            ->assertSessionHasAll(['success' => 'Payment canceled.']);

        $this->assertEquals($order->payment_status, 'pending');

        $order = $order->fresh();

        $this->assertEquals($order->payment_status, 'canceled');
    }

    public function test_redirect_to_error_page_when_the_system_has_not_authorization_to_connect_the_payment_platform(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        $mock_response = [
            "status" => [
                "status" => "FAILED",
                "reason" => 401,
                "message" => "Autenticación fallida 101",
                "date" => "2023-06-05T10:26:44-05:00"
            ]
        ];

        Http::fake([
            config('placetopay.url').'/*' => Http::response($mock_response, 401)
        ]);

        $this->patchJson(route('payment.update', $order->getKey()), [
            'id' => $order->getKey(),
            'payment_method' => 'NORMAL',
        ])->assertRedirect(route('payment.error', 401));
    }

    public function test_redirect_to_error_page_when_there_is_an_internal_server_error(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        Http::fake([
            config('placetopay.url').'/*' => Http::response(['error' => '500'], 500)
        ]);

        $this->patchJson(route('payment.update', $order->getKey()), [
            'id' => $order->getKey(),
            'payment_method' => 'NORMAL',
        ])->assertRedirect(route('payment.error', 500));
    }

    public function test_redirect_to_error_page_when_the_service_is_unavailable(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
        ]);

        Http::fake([
            config('placetopay.url').'/*' => Http::response(['error' => '503'], 503)
        ]);

        $this->patchJson(route('payment.update', $order->getKey()), [
            'id' => $order->getKey(),
            'payment_method' => 'NORMAL',
        ])->assertRedirect(route('payment.error', 503));
    }

    public function test_can_redirect_to_showcase_page_from_process_error_401(): void
    {
        $this->actingAs($this->user)
            ->get(route('payment.error', 401))
            ->assertRedirect(route('showcase.index'));
    }

    public function test_can_redirect_to_showcase_page_from_process_error_500(): void
    {
        $this->actingAs($this->user)
            ->get(route('payment.error', 500))
            ->assertRedirect(route('showcase.index'));
    }

    public function test_can_redirect_to_showcase_page_from_process_error_503(): void
    {
        $this->actingAs($this->user)
            ->get(route('payment.error', 503))
            ->assertRedirect(route('showcase.index'));
    }

    public function test_can_report_error_when_process_response_was_not_successfull(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
            'url' => 'https://checkout-co.placetopay.dev/spa/session/0000/0000',
            'request_id' => 0000,
        ]);

        Http::fake([
            config('placetopay.url').'/*' => Http::response(['error' => '500'], 500)
        ]);

        $this->get(route('payment.response', $order->code))
            ->assertRedirect(route('order.show', $order->getKey()))
            ->assertSessionHasAll(['success' => 'Payment error.']);
    }

    public function test_can_report_unknown_status_in_logs(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
            'url' => 'https://checkout-co.placetopay.dev/spa/session/0000/0000',
            'request_id' => 0000,
        ]);

        $mock_response = [
            "requestId" => 0000,
            "status" => [
                "status" => "APPROVED_PART",
                "reason" => "AP",
                "message" => "La petición aprobada parcialmente.",
                "date" => "2023-06-04T22:01:16-05:00"
            ]
        ];

        Http::fake([
            config('placetopay.url').'/*' => Http::response($mock_response)
        ]);

        $this->get(route('payment.response', $order->code))
            ->assertRedirect(route('order.show', $order->getKey()));
    }

    public function test_can_report_approval_partial_in_allow_partial_payment(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
            'url' => 'https://checkout-co.placetopay.dev/spa/session/0000/0000',
            'request_id' => 0000,
        ]);

        $mock_response = [
            "requestId" => 0000,
            "status" => [
                "status" => "APPROVED_PARTIAL",
                "reason" => "P0",
                "message" => "La petición está parcialmente aprobada",
                "date" => "2023-06-12T18:24:03-05:00",
            ]
        ];


        Http::fake([config('placetopay.url').'/*' => Http::response($mock_response)]);

        $this->getJson(route('payment.response', $order->code))
            ->assertRedirectContains('order/'.$order->getKey());

        $this->assertEquals($order->payment_status, 'pending');

        $order = $order->fresh();

        $this->assertEquals($order->payment_status, 'approved_partial');
    }

    public function test_can_report_partial_expired_in_allow_partial_payment(): void
    {
        $this->actingAs($this->user);
        $order = Order::factory()->create([
            'user_id' => $this->user->getKey(),
            'url' => 'https://checkout-co.placetopay.dev/spa/session/0000/0000',
            'request_id' => 0000,
        ]);

        $mock_response = [
            "requestId" => 0000,
            "status" => [
                "status" => "PARTIAL_EXPIRED",
                "reason" => "PX",
                "message" => "La petición esta expirada o cancelada y se han realizado pagos",
                "date" => "2023-06-12T18:24:05-05:00"
            ]
        ];


        Http::fake([config('placetopay.url').'/*' => Http::response($mock_response)]);

        $this->getJson(route('payment.response', $order->code))
            ->assertRedirectContains('order/'.$order->getKey());

        $this->assertEquals($order->payment_status, 'pending');

        $order = $order->fresh();

        $this->assertEquals($order->payment_status, 'partial_expired');
    }
}
