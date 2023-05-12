<?php

namespace App\Actions\Product;

use App\Dtos\Product\StoreProductData;
use App\Models\Product;
use App\Services\Product\ImagesServices;
use Illuminate\Database\Eloquent\Model;

class StoreProductAction
{
    public function __construct(
        protected ImagesServices $images,
    )
    {}

    public function handle(StoreProductData $data): Model
    {
        return Product::create($this->images->save($data));
    }
}
