<?php

namespace App\Domain\User\Dtos\State;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStateData
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
