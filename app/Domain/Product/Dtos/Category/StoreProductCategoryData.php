<?php

namespace App\Domain\Product\Dtos\Category;

use Illuminate\Foundation\Http\FormRequest;

/** @phpstan-consistent-constructor */
class StoreProductCategoryData
{
    public function __construct(
        public string $name,
    )
    {}

    public static function fromRequest(FormRequest $request): self
    {
        return new static(
            name: $request->input('name'),
        );
    }
}
