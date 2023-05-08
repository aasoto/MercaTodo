<?php

namespace App\Actions\Product;

use App\Dtos\Product\UpdateProductData;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Models\Product;
use App\Services\Product\ImagesServices;

class UpdateProductAction
{
    public function handle(UpdateProductData $data, ImagesServices $images, string $id, string $files): string
    {
        $data = $images->Update($data, $files);

        Product::where('id', $id)->update($data);

        return $data['slug'];
    }
}
