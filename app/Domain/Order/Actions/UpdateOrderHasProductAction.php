<?php

namespace App\Domain\Order\Actions;

use App\Domain\Order\Models\OrderHasProduct;
use App\Domain\Order\Dtos\UpdateOrderHasProductData;
use App\Domain\Product\Models\Product;

class UpdateOrderHasProductAction
{
    public function handle(UpdateOrderHasProductData $data): void
    {
        OrderHasProduct::where('order_id', $data->order_id)
            ->where('product_id', $data->product_id)
            ->update([
                'quantity' => $data->quantity,
                'price' => $data->price,
            ]);

        /**
         * @var Product $product
         */
        $product = Product::select('stock')->where('id', $data->product_id)->first();

        Product::where('id', $data->product_id)->update([
            'stock' => intval($product['stock']) - $data->quantity,
        ]);
    }
}
