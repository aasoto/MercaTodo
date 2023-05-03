<?php

namespace App\Actions\Product;

use App\Classes\Product\Images;
use App\Models\Product;

class DestroyProductAction
{
    public function handle(
        Images $images,
        ShowProductAction $showAction,
        string $slug): bool
    {
        $query = $showAction->handle($slug);

        $images->Delete($query ? $query->toArray() : []);

        return Product::where('slug', $slug)->delete();
    }
}
