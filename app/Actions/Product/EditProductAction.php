<?php

namespace App\Actions\Product;

use App\Models\Product;

class EditProductAction
{
    public function handle(string $slug): Product|null
    {
        return Product::select(
            'products.id',
            'products.name',
            'products.products_category_id',
            'products.barcode',
            'products.description',
            'products.price',
            'products.unit',
            'products.stock',
            'products.picture_1',
            'products.picture_2',
            'products.picture_3',
            'products.availability'
        )
        -> where('slug', $slug)
        -> first();
    }
}
