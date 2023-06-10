<?php

namespace App\Domain\Order\Actions;

use App\Domain\Order\Models\OrderHasProduct;
use App\Domain\Product\Models\Product;

class GetProductsByOrderAction
{
    /**
     * @param string $order_id
     * @return array<mixed>
     */
    public function handle(string $order_id): array
    {
        $products_by_order = OrderHasProduct::select('product_id', 'quantity', 'price')
            ->where('order_id', $order_id)
            ->get();

        $products_data = array();

        foreach ($products_by_order as $key => $value) {
            /**
             * @var Product $product
             */
            $product = Product::where('id', $value['product_id'])->first();

            array_push($products_data, [
                'id' => $product['id'],
                'name' => $product['name'],
                'slug' => $product['slug'],
                'quantity' => $value['quantity'],
                'price' => $value['price'],
                'totalPrice' => $value['quantity'] * $value['price'],
            ]);
        }

        return $products_data;
    }
}
