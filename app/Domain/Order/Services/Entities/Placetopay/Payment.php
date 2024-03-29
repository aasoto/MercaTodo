<?php

namespace App\Domain\Order\Services\Entities\Placetopay;

use App\Domain\Order\Dtos\StoreOrderData;
use App\Domain\Order\Models\Order;

class Payment
{
    public function __construct(
        public Order $order,
        public StoreOrderData $products_order,
        public string $currency = 'COP',
    )
    {}

    /**
     * @return array<mixed>
     */
    public function getPayment(): array
    {
        return [
            'reference' => $this->order->code,
            'description' => 'Payment of purchase total',
            'amount' => $this->amount(),
            'allowPartial' => $this->allowPartial($this->products_order->payment_method),
            'items' => $this->items(),
        ];
    }

    /**
     * @return array<mixed>
     */
    private function amount(): array
    {
        return [
            'currency' => $this->currency,
            'total' => $this->order->purchase_total,
        ];
    }

    /**
     * @return array<mixed>
     */
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

    private function allowPartial(string $method): bool
    {
        return $method == 'ALLOW_PARTIAL' ? true : false;
    }
}
