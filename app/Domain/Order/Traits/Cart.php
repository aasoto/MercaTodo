<?php

namespace App\Domain\Order\Traits;

use App\Domain\Order\Models\Order;
use App\Domain\Product\Models\Product;

trait Cart
{
    /**
     * @param bool $has_products
     * @param ?array<mixed> $order
     * @param ?Order $order_saved
     * @return array<mixed>
     */
    public function get_cart(bool $has_products, ?array $order, ?Order $order_saved): array
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
            foreach ($order_saved->products as $key => $value) {
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
