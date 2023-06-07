<?php

namespace App\Domain\Product\Actions;

use App\Domain\Product\Dtos\UpdateProductData;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Services\ImagesServices;

class UpdateProductAction
{
    public function __construct(
        protected ImagesServices $images,
    )
    {}

    public function handle(UpdateProductData $data, string $id, string $files): string
    {
        $data = $this->images->Update($data, $files);

        Product::where('id', $id)->update($data);

        return $data['slug'];
    }
}
