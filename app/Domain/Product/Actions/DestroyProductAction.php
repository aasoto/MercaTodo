<?php

namespace App\Domain\Product\Actions;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Services\ImagesServices;

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

        if ($query) {
            $this->images->Delete($query->toArray());
            return Product::where('slug', $slug)->delete();
        } else {
            return false;
        }
    }
}
