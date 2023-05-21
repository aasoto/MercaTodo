<?php

namespace App\Domain\User\Dtos\City;

use Illuminate\Foundation\Http\FormRequest;

/** @phpstan-consistent-constructor */
class StoreCityData
{
    public function __construct(
        public string $name,
        public int $state_id,
    )
    {}

    public static function fromRequest(FormRequest $request): self
    {
        return new static(
            name: $request->input('name'),
            state_id: $request->input('state_id'),
        );
    }
}
