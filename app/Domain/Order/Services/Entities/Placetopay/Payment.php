<?php

namespace App\Domain\Order\Services\Entities\Placetopay;

use App\Domain\Order\Dtos\StoreOrderData;
use Illuminate\Database\Eloquent\Model;

class Payment
{
    public function __construct(
        public Model $order,
        public StoreOrderData $products_order,
        public string $currency = 'COP',
    )
    {}

    public function getPayment(): array
    {
        return [
            'reference' => $this->order->code,
            'description' => 'Payment of purchase total',
            'amount' => $this->amount(),
            'items' => $this->items(),
        ];
    }

    private function amount(): array
    {
        return [
            'currency' => $this->currency,
            'total' => $this->order->purchase_total,
        ];
    }

    private function items(): array
    {
        $items = array();

        foreach ($this->products_order->products as $key => $value) {
            array_push($items, [
                'name' => ucwords($value['name']),
                'qty' => $value['quantity'],
                'price' => $value['totalPrice'],
            ]);
        }
        return $items;
    }
}
