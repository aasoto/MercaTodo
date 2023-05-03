<?php

namespace App\Actions\Product;

use App\Classes\Product\Images;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Models\Product;

class StoreProductAction
{
    public function handle(StoreRequest $request, Images $images): Product
    {
        return Product::create($images->save($request->validated()));
    }
}
