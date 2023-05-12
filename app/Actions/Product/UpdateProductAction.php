<?php

namespace App\Actions\Product;

use App\Dtos\Product\UpdateProductData;
use App\Models\Product;
use App\Services\Product\ImagesServices;

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
