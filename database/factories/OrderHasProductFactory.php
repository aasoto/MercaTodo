<?php

namespace Database\Factories;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderHasProduct;
use App\Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class OrderHasProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = OrderHasProduct::class;

    public function definition(): array
    {
        do {
            $order = Order::select('id')->inRandomOrder()->first();
            $product = Product::select('id', 'price')->inRandomOrder()->first();
            $found = OrderHasProduct::where('order_id', $order['id'])->where('product_id', $product['id'])->get();
        } while (count($found) != 0);

        return [
            'order_id' => $order['id'],
            'product_id' => $product['id'],
            'quantity' => fake()->randomDigitNotNull(),
            'price' => $product['price'],
        ];
    }
}
