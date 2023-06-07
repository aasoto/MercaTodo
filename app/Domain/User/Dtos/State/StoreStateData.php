<?php

namespace App\Domain\User\Dtos\State;

use Illuminate\Foundation\Http\FormRequest;

/** @phpstan-consistent-constructor */
class StoreStateData
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
