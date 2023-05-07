<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Services\Product\ImagesServices;

class DestroyProductAction
{
    public function handle(
        ImagesServices $images,
        ShowProductAction $showAction,
        string $slug): bool
    {
        $query = $showAction->handle($slug);

        $images->Delete($query ? $query->toArray() : []);

        return Product::where('slug', $slug)->delete();
    }
}
