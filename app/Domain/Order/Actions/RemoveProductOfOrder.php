<?php

namespace App\Domain\Order\Actions;

use App\Domain\Order\Models\OrderHasProduct;

class RemoveProductOfOrder
{
    public function handle(string $order_id, string $product_id): void
    {
        OrderHasProduct::where('product_id', $product_id)
            ->where('order_id', $order_id)
            ->delete();
    }
}
