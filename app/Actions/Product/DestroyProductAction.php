<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Services\Product\ImagesServices;

class DestroyProductAction
{
    public function __construct(
        protected ImagesServices $images,
        protected ShowProductAction $show_product_action,
    )
    {}

    public function handle(string $slug): bool
    {
        $query = $this->show_product_action->handle($slug);

        $this->images->Delete($query ? $query->toArray() : []);

        return Product::where('slug', $slug)->delete();
    }
}
