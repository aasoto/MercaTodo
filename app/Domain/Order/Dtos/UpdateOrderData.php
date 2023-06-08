<?php

namespace App\Domain\Order\Dtos;

/** @phpstan-consistent-constructor */
class UpdateOrderData
{
    public function __construct(
        public string $payment_date,
    )
    {}

    public static function fromResult(string $date): self
    {
        /**
         * @var int|null $timestamp
         */
        $timestamp = strtotime($date);

        return new static(
            payment_date: date('Y-m-d H:i:s', $timestamp),
        );
    }
}
