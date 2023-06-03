<?php

namespace App\Domain\Order\Services\Entities\Placetopay;

use Illuminate\Database\Eloquent\Model;

class Payment
{
    public function __construct(
        public Model $order,
        public string $currency = 'COP',
    )
    {}

    public function getPayment(): array
    {
        return [
            'reference' => $this->order->code,
            'description' => 'Payment of purchase total',
            'amount' => $this->amount(),
        ];
    }

    private function amount(): array
    {
        return [
            'currency' => $this->currency,
            'total' => $this->order->purchase_total,
        ];
    }
}
