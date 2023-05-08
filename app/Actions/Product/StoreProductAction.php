<?php

namespace App\Actions\Product;

use App\Dtos\Product\StoreProductData;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Models\Product;
use App\Services\Product\ImagesServices;
use Illuminate\Database\Eloquent\Model;

class StoreProductAction
{
    public function handle(StoreProductData $data, ImagesServices $images): Model
    {
        return Product::create($images->save($data));
    }
}
