<?php

namespace App\Domain\Product\Dtos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/** @phpstan-consistent-constructor */
class ImportProductData
{
    public function __construct(
        public string $products_file,
    )
    {}

    public static function fromRequest(FormRequest $request): self
    {
        return new static(
            products_file: $request->file('products_file')->store('imports'),
        );
    }
}
