<?php

namespace App\Domain\User\Dtos\TypeDocument;

use Illuminate\Foundation\Http\FormRequest;

/** @phpstan-consistent-constructor */
class StoreTypeDocumentData
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
