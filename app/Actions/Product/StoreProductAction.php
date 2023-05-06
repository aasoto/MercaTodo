<?php

namespace App\Actions\Product;

use App\Classes\Product\Images;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class StoreProductAction
{
    public function handle(StoreRequest $request, Images $images): Model
    {
        return Product::create($images->save($request->validated()));
    }
}
