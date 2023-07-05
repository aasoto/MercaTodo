<?php

namespace App\Domain\Product\Dtos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/** @phpstan-consistent-constructor */
class ImageProductData
{
    public function __construct(
        public UploadedFile $image_file,
    )
    {}

    public static function fromRequest(FormRequest $request): self
    {
        return new static(
            image_file: $request->file('image_file'),
        );
    }
}
