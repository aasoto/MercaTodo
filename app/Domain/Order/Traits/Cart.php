<?php

namespace App\Domain\Order\Traits;

use App\Domain\Order\Models\Order;
use App\Domain\Product\Models\Product;

trait Cart
{
    /**
     * @return array<mixed>
     */
    public function get_cart(Order $order): array
    {
        $cart = array();
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

        return $cart;
    }
}
