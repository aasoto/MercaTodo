<?php

namespace App\Domain\Order\Dtos;

use Illuminate\Foundation\Http\FormRequest;

class IndexOrderData
{
    public function __construct(
        public int $user_id,
    )
    {}

    public static function fromRequest(FormRequest $request): self
    {
        return new static(
            user_id: $request->input('user_id'),
        );
    }
}
