<?php

namespace App\Domain\Product\Dtos\Unit;

use Illuminate\Foundation\Http\FormRequest;

/** @phpstan-consistent-constructor */
class StoreUnitData
{
    public function __construct(
        public string $code,
        public string $name,
    )
    {}

    public static function fromRequest(FormRequest $request): self
    {
        return new static(
            code: $request->input('code'),
            name: $request->input('name'),
        );
    }
}
