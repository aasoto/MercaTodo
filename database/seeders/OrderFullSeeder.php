<?php

namespace Database\Seeders;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderHasProduct;
use App\Domain\Product\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderFullSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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

        /**
         * @var Order $order
         */
        $order = Order::factory()->create([
            'purchase_total' => $total_order,
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
