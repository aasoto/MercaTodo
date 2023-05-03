<?php

namespace App\Actions\Product;

use App\Classes\Product\Images;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Models\Product;

class UpdateProductAction
{
    public function handle(UpdateRequest $request, Images $images, string $id, string $files): string
    {
        $data = $images->Update($request->validated(), $files);

        Product::where('id', $id)->update($data);

        return $data['slug'];
    }
}
