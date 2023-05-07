<?php

namespace App\Actions\Product;

use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Models\Product;
use App\Services\Product\ImagesServices;

class UpdateProductAction
{
    public function handle(UpdateRequest $request, ImagesServices $images, string $id, string $files): string
    {
        $data = $images->Update($request->validated(), $files);

        Product::where('id', $id)->update($data);

        return $data['slug'];
    }
}
