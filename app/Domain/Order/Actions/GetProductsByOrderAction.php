<?php

namespace App\Domain\Order\Actions;

use App\Domain\Order\Models\OrderHasProduct;
use App\Domain\Product\Models\Product;

class GetProductsByOrderAction
{
    /**
     * @param string $order_id
     * @param bool $has_products
     * @param ?array<mixed> $products
     * @return array<mixed>
     */
    public function handle(string $order_id, bool $has_products, ?array $products): array
    {
        $products_data = array();

        if (!$has_products) {
            $products_by_order = OrderHasProduct::select('product_id', 'quantity', 'price')
                ->where('order_id', $order_id)
                ->get();

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
        } elseif($products) {
            foreach ($products as $key => $value) {
                /**
                 * @var Product $product
                 */
                $product = Product::where('id', $value['id'])->first();

                array_push($products_data, [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'slug' => $product['slug'],
                    'quantity' => $value['quantity'],
                    'price' => $value['price'],
                    'totalPrice' => $value['quantity'] * $value['price'],
                ]);
            }
        }

        return $products_data;
    }
}
