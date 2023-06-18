<?php

namespace App\Domain\Order\Dtos;

use Illuminate\Foundation\Http\FormRequest;

/** @phpstan-consistent-constructor */
class StoreOrderData
{
    /**
     * @param array<mixed> $products
     */
    public function __construct(
        public array $products,
        public string $payment_method,
    )
    {}

    public static function fromRequest(FormRequest $request): self
    {
        return new static(
            products: $request->input('products'),
            payment_method: $request->input('payment_method'),
        );
    }
}
