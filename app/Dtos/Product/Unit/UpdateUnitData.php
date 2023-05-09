<?php

namespace App\Dtos\Product\Unit;

use Illuminate\Foundation\Http\FormRequest;

/** @phpstan-consistent-constructor */
class UpdateUnitData
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
