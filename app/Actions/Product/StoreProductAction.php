<?php

namespace App\Actions\Product;

use App\Http\Requests\Admin\Product\StoreRequest;
use App\Models\Product;
use App\Services\Product\ImagesServices;
use Illuminate\Database\Eloquent\Model;

class StoreProductAction
{
    public function handle(StoreRequest $request, ImagesServices $images): Model
    {
        return Product::create($images->save($request->validated()));
    }
}
