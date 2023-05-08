<?php

namespace App\Dtos\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/** @phpstan-consistent-constructor */
class StoreProductData
{
    public function __construct(
        public string $name,
        public string $slug,
        public string $products_category_id,
        public string $barcode,
        public string|null $description,
        public string $price,
        public string $unit,
        public string $stock,
        public UploadedFile $picture_1,
        public UploadedFile|null $picture_2,
        public UploadedFile|null $picture_3,
    )
    {}

    public static function fromRequest(FormRequest $request): self
    {
        return new static(
            name: $request->input('name'),
            slug: $request->validated('slug'),
            products_category_id: $request->input('products_category_id'),
            barcode: $request->input('barcode'),
            description: $request->input('description'),
            price: $request->input('price'),
            unit: $request->input('unit'),
            stock: $request->input('stock'),
            picture_1: $request->file('picture_1'),
            picture_2: $request->file('picture_2'),
            picture_3: $request->file('picture_3'),
        );
    }
}
