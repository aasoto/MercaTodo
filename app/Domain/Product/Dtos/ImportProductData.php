<?php

namespace App\Domain\Product\Dtos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/** @phpstan-consistent-constructor */
class ImportProductData
{
    public function __construct(
        public string $products_file,
    )
    {}

    public static function fromRequest(FormRequest $request): self
    {
        /**
         * @var UploadedFile $file;
         */
        $file = $request->file('products_file');

        return new static(
            products_file: Storage::disk(config()->get('filesystem.default'))->put('imports', $file),
        );
    }
}
