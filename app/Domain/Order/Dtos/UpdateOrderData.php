<?php

namespace App\Domain\Order\Dtos;

/** @phpstan-consistent-constructor */
class UpdateOrderData
{
    public function __construct(
        public string|null $payment_date,
        public string $purchase_total,
    )
    {}

    public static function fromResult(string $date, string $purchase_total): self
    {
        /**
         * @var int|null $timestamp
         */
        $timestamp = strtotime($date);

        return new static(
            payment_date: date('Y-m-d H:i:s', $timestamp),
            purchase_total: $purchase_total,
        );
    }
}
