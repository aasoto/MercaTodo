<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Services\Product\ImagesServices;

class DestroyProductAction
{
    public function __construct(
        protected ImagesServices $images,
    )
    {}

    public function handle(string $slug): bool
    {
        $query = Product::select(
            'picture_1',
            'picture_2',
            'picture_3')
        ->whereSlug($slug)
        ->first();

        $this->images->Delete($query ? $query->toArray() : []);

        return Product::where('slug', $slug)->delete();
    }
}
