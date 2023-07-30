<?php

namespace Tests\Feature\Http\Controllers\Web\Admin\Report;

use App\Domain\Order\Models\Order;
use App\Domain\Order\QueryBuilders\OrderQueryBuilder;
use App\Domain\Product\Models\ProductCategory;
use App\Domain\Product\Models\Unit;
use App\Domain\User\Models\City;
use App\Domain\User\Models\State;
use App\Domain\User\Models\TypeDocument;
use App\Domain\User\Models\User;
use App\Http\Exports\OrdersReport;
use Database\Seeders\RoleSeeder;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ReportOrdersTest extends TestCase
{
    use RefreshDatabase;

    private User $user_admin, $user_client;
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

        $this->user_admin = User::factory()->create()->assignRole('admin');
        $this->user_client = User::factory()->create()->assignRole('client');
        $this->order = Order::factory()->create([
            'code' => '123abc',
            'user_id' => $this->user_client->id,
            'purchase_date' => '2023-07-01 12:00:00',
            'purchase_total' => '500000',
        ]);
    }

    public function test_can_search_order_applaying_all_filters(): void
    {
        $response = $this->actingAs($this->user_admin)
            ->get('order_report?numberDocument='.$this->user_client->number_document.'&date1=2023-06-30&date2=2023-07-02&paymentStatus=pending&minTotal=450000&maxTotal=600000');

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/Order/Create')
                -> has('filters')
                -> has('orders.data.0', fn (Assert $page) => $page
                    -> where('id', $this->order->id)
                    -> where('code', $this->order->code)
                    -> where('purchase_date', $this->order->purchase_date)
                    -> where('payment_date', $this->order->payment_date)
                    -> where('payment_status', $this->order->payment_status)
                    -> where('purchase_total', intval($this->order->purchase_total))
                    -> where('url', $this->order->url)
                    -> where('first_name', $this->user_client->first_name)
                    -> where('second_name', $this->user_client->second_name)
                    -> where('surname', $this->user_client->surname)
                    -> where('second_surname', $this->user_client->second_surname)
                    -> etc()
                )
                -> has('success')
        );
    }

    public function test_can_search_orders_by_date_1(): void
    {
        $response = $this->actingAs($this->user_admin)
            ->get('order_report?&date1='.$this->order->purchase_date);

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/Order/Create')
                -> has('filters')
                -> has('orders.data.0', fn (Assert $page) => $page
                    -> where('id', $this->order->id)
                    -> where('code', $this->order->code)
                    -> where('purchase_date', $this->order->purchase_date)
                    -> where('payment_date', $this->order->payment_date)
                    -> where('payment_status', $this->order->payment_status)
                    -> where('purchase_total', intval($this->order->purchase_total))
                    -> where('url', $this->order->url)
                    -> where('first_name', $this->user_client->first_name)
                    -> where('second_name', $this->user_client->second_name)
                    -> where('surname', $this->user_client->surname)
                    -> where('second_surname', $this->user_client->second_surname)
                    -> etc()
                )
                -> has('success')
        );
    }

    public function test_can_search_orders_by_date_2(): void
    {
        $response = $this->actingAs($this->user_admin)
            ->get('order_report?&date2='.$this->order->purchase_date);

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/Order/Create')
                -> has('filters')
                -> has('orders.data.0', fn (Assert $page) => $page
                    -> where('id', $this->order->id)
                    -> where('code', $this->order->code)
                    -> where('purchase_date', $this->order->purchase_date)
                    -> where('payment_date', $this->order->payment_date)
                    -> where('payment_status', $this->order->payment_status)
                    -> where('purchase_total', intval($this->order->purchase_total))
                    -> where('url', $this->order->url)
                    -> where('first_name', $this->user_client->first_name)
                    -> where('second_name', $this->user_client->second_name)
                    -> where('surname', $this->user_client->surname)
                    -> where('second_surname', $this->user_client->second_surname)
                    -> etc()
                )
                -> has('success')
        );
    }

    public function test_can_search_orders_by_purchase_total_1(): void
    {
        $response = $this->actingAs($this->user_admin)
            ->get('order_report?&minTotal='.$this->order->purchase_total);

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/Order/Create')
                -> has('filters')
                -> has('orders.data.0', fn (Assert $page) => $page
                    -> where('id', $this->order->id)
                    -> where('code', $this->order->code)
                    -> where('purchase_date', $this->order->purchase_date)
                    -> where('payment_date', $this->order->payment_date)
                    -> where('payment_status', $this->order->payment_status)
                    -> where('purchase_total', intval($this->order->purchase_total))
                    -> where('url', $this->order->url)
                    -> where('first_name', $this->user_client->first_name)
                    -> where('second_name', $this->user_client->second_name)
                    -> where('surname', $this->user_client->surname)
                    -> where('second_surname', $this->user_client->second_surname)
                    -> etc()
                )
                -> has('success')
        );
    }

    public function test_can_search_orders_by_purchase_total_2(): void
    {
        $response = $this->actingAs($this->user_admin)
            ->get('order_report?&maxTotal='.$this->order->purchase_total);

        $response->assertStatus(200)->assertInertia(
            fn (Assert $page) => $page
                -> component('Report/Order/Create')
                -> has('filters')
                -> has('orders.data.0', fn (Assert $page) => $page
                    -> where('id', $this->order->id)
                    -> where('code', $this->order->code)
                    -> where('purchase_date', $this->order->purchase_date)
                    -> where('payment_date', $this->order->payment_date)
                    -> where('payment_status', $this->order->payment_status)
                    -> where('purchase_total', intval($this->order->purchase_total))
                    -> where('url', $this->order->url)
                    -> where('first_name', $this->user_client->first_name)
                    -> where('second_name', $this->user_client->second_name)
                    -> where('surname', $this->user_client->surname)
                    -> where('second_surname', $this->user_client->second_surname)
                    -> etc()
                )
                -> has('success')
        );
    }

    public function test_can_queue_report_of_orders(): void
    {
        Excel::fake();

        $time = strval(time());

        $response = $this->actingAs($this->user_admin)
            ->post(route('order.report.export'), [
                'number_document' => strval($this->user_client->number_document),
                'time' => $time,
            ]);

        Excel::assertQueued('reports/orders/orders_'.$time.'.xlsx');

        $response->assertRedirect(route('order.report.create'))
        ->assertSessionHasAll(['success' => 'Orders report generated.']);
    }

    public function test_can_return_view_with_the_expected_order_from_the_export_class(): void
    {
        $orders_report = new OrdersReport([
            'min_total' => $this->order->purchase_total,
        ]);

        $response = $orders_report->view();

        $this->assertInstanceOf(View::class, $response);
    }
}
