<?php

namespace App\Domain\Product\Dtos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/** @phpstan-consistent-constructor */
class ImageProductData
{
    public function __construct(
        public ?int $product_id,
        public ?int $picture_number,
        public UploadedFile $image_file,
    )
    {}

    public static function fromRequest(FormRequest $request): self
    {
        return new static(
            product_id: $request->input('product_id'),
            picture_number: $request->input('picture_number'),
            image_file: $request->file('image_file'),
        );
    }
}
