<?php

namespace App\Domain\Product\Actions;

use App\Domain\Product\Dtos\StoreProductData;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Services\ImagesServices;
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
