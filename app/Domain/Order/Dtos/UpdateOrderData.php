<?php

namespace App\Domain\Order\Dtos;

class UpdateOrderData
{
    public function __construct(
        public string $payment_date,
    )
    {}

    public static function fromResult(string $date): self
    {
        return new static(
            payment_date: date('Y-m-d H:i:s', strtotime($date)),
        );
    }
}
