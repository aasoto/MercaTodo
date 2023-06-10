<?php

namespace App\Domain\Order\Dtos;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderHasProductData
{
    public function __construct(
        public string $order_id,
        public string $product_id,
        public string $quantity,
        public string $price,
    )
    {}

    public static function fromRequest(FormRequest $request): self
    {
        return new static(
            order_id: $request->input('order_id'),
            product_id: $request->input('product_id'),
            quantity: $request->input('quantity'),
            price: $request->input('price'),
        );
    }

}
