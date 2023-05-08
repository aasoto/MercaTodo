<?php

namespace App\Dtos\User\TypeDocument;

use Illuminate\Foundation\Http\FormRequest;

/** @phpstan-consistent-constructor */
class UpdateTypeDocumentData
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
