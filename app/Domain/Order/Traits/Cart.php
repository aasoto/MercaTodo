<?php

namespace App\Domain\Order\Traits;

use App\Domain\Order\Models\Order;
use App\Domain\Product\Models\Product;

trait Cart
{
    /**
     * @param bool $has_products
     * @param Order|array<mixed> $order
     * @return array<mixed>
     */
    public function get_cart(bool $has_products, Order|array $order): array
    {
        $cart = array();

        if ($has_products) {
            foreach ($order as $key => $value) {
                array_push($cart, [
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'slug' => $value['slug'],
                    'quantity' => $value['quantity'],
                ]);
            }
        } else {
            foreach ($order->products as $key => $value) {
                /**
                 * @var Product $product
                 */
                $product = $value->product;
                array_push($cart, [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'quantity' => $value->quantity,
                ]);
            }
        }

        return $cart;
    }
}
