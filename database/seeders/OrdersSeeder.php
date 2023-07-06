<?php

namespace Database\Seeders;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderHasProduct;
use App\Domain\Product\Models\Product;
use App\Domain\User\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=0; $i < 5; $i++) {
            $user = User::factory()->create()->assignRole('client');
            for ($j=0; $j < 5; $j++) {
                $this->make_order($user);
            }
        }
    }

    private function make_order(User $user): void
    {
        $products_order = array();
        $total_order = 0;

        $products = Product::select('id', 'price')->inRandomOrder()->take(3)->get();

        foreach ($products as $key => $value) {

            $quantity = rand(1, 4);

            array_push($products_order, [
                'id' => $value['id'],
                'price' => $value['price'],
                'quantity' => $quantity,
            ]);

            $total_order = $total_order + ($value['price'] * $quantity);
        }

        $current_date = date('Y-m-d');
        $days = rand(0, 8);
        /**
         * @var Order $order
         */
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'purchase_total' => $total_order,
            'purchase_date' => date('Y-m-d H:i:s', strtotime($current_date.'- '.$days.' days')),
            'payment_status' => config('paymentStatus')[array_rand(config('paymentStatus'))],
        ]);

        foreach ($products_order as $key => $value) {
            OrderHasProduct::factory()->create([
                'order_id' => $order->id,
                'product_id' => $value['id'],
                'quantity' => $value['quantity'],
                'price' => $value['price'],
            ]);
        }
    }
}
