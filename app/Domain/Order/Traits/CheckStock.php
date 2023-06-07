<?php

namespace App\Domain\Order\Traits;

use App\Domain\Order\Dtos\StoreOrderData;
use App\Domain\Product\Models\Product;

trait CheckStock
{
    /**
     * @return array<mixed>
     */
    public function solvent_order(StoreOrderData $data): array
    {
        $limitated_stock = array();
        foreach ($data->products as $key => $value) {

            /**
             * @var Product $stock_available
             */
            $stock_available = Product::select('stock')->where('id', $value['id'])->first();
            if ($value['quantity'] > $stock_available['stock']) {
                array_push($limitated_stock, [
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'slug' => $value['slug'],
                    'stock' => $stock_available['stock'],
                ]);
            }
        }

        return $limitated_stock;
    }
}
