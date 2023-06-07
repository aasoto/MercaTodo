<?php

namespace App\Domain\Order\Actions;

use App\Domain\Order\Dtos\StoreOrderData;
use App\Domain\Order\Models\OrderHasProduct;
use App\Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;

class StoreOrderHasProductAction
{
    public function handle(StoreOrderData $data, Model $order): void
    {
        foreach ($data->products as $key => $value) {
            OrderHasProduct::create([
                'order_id' => $order['id'],
                'product_id' => $value['id'],
                'quantity' => $value['quantity'],
                'price' => $value['price'],
            ]);

            /**
             * @var Product $product
             */
            $product = Product::select('stock')->where('id', $value['id'])->first();

            Product::where('id', $value['id'])->update([
                'stock' => intval($product['stock']) - $value['quantity'],
            ]);
        }


    }
}
